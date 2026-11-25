<?php
// VMAX Software - Support Tickets Controller

class SupportController {
    
    public function index() {
        if (!Auth::check()) {
            redirect('/login');
        }
        
        $user = Auth::user();
        $ticketModel = new TicketModel();
        
        $error = '';
        $message = '';
        
        // Handle new ticket creation or reply
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $csrf = $_POST['csrf_token'] ?? '';
            
            if (!Auth::validateCsrf($csrf)) {
                $error = 'Mã bảo mật CSRF không hợp lệ.';
            } else {
                if ($_POST['action'] === 'create_ticket') {
                    $subject = sanitize($_POST['subject'] ?? '');
                    $priority = sanitize($_POST['priority'] ?? 'medium');
                    $content = sanitize($_POST['message'] ?? '');
                    
                    if (empty($subject) || empty($content)) {
                        $error = 'Tiêu đề và nội dung yêu cầu hỗ trợ không được bỏ trống.';
                    } else {
                        $ticketId = $ticketModel->createTicket($user['id'], $subject, $priority);
                        
                        // Handle file attachment upload if exists
                        $attachmentPath = null;
                        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                            $fileName = time() . '_' . basename($_FILES['attachment']['name']);
                            $dest = BASE_PATH . '/assets/uploads/' . $fileName;
                            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $dest)) {
                                $attachmentPath = 'assets/uploads/' . $fileName;
                            }
                        }
                        
                        $ticketModel->addMessage($ticketId, $user['id'], $content, $attachmentPath);
                        $message = 'Ticket hỗ trợ đã được tạo thành công!';
                    }
                } else if ($_POST['action'] === 'reply_ticket') {
                    $ticketId = intval($_POST['ticket_id'] ?? 0);
                    $content = sanitize($_POST['message'] ?? '');
                    
                    $ticket = $ticketModel->getTicketById($ticketId);
                    if (!$ticket || $ticket['user_id'] != $user['id']) {
                        $error = 'Yêu cầu hỗ trợ không tồn tại hoặc bạn không có quyền truy cập.';
                    } else if (empty($content)) {
                        $error = 'Nội dung phản hồi không được bỏ trống.';
                    } else {
                        $attachmentPath = null;
                        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                            $fileName = time() . '_' . basename($_FILES['attachment']['name']);
                            $dest = BASE_PATH . '/assets/uploads/' . $fileName;
                            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $dest)) {
                                $attachmentPath = 'assets/uploads/' . $fileName;
                            }
                        }
                        
                        $ticketModel->addMessage($ticketId, $user['id'], $content, $attachmentPath);
                        $message = 'Gửi phản hồi thành công!';
                        // Redirect to the same ticket thread to avoid form resubmission
                        redirect('/support?ticket_id=' . $ticketId);
                    }
                }
            }
        }
        
        $tickets = $ticketModel->getTicketsByUser($user['id']);
        
        $activeTicket = null;
        $messages = [];
        $ticketId = isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : 0;
        if ($ticketId > 0) {
            $ticket = $ticketModel->getTicketById($ticketId);
            if ($ticket && $ticket['user_id'] == $user['id']) {
                $activeTicket = $ticket;
                $messages = $ticketModel->getMessages($ticketId);
            }
        }
        
        view('dashboard/support', [
            'tickets' => $tickets,
            'activeTicket' => $activeTicket,
            'messages' => $messages,
            'error' => $error,
            'message' => $message
        ]);
    }
}
