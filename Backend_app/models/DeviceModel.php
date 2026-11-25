<?php
// VMAX Software - Device Model

class DeviceModel {
    private $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public function findByLicenseAndDevice($licenseId, $deviceId) {
        $stmt = $this->db->query("SELECT * FROM devices WHERE license_id = ? AND device_id = ?", [$licenseId, $deviceId]);
        return $stmt->fetch();
    }

    public function getActiveDevicesByLicense($licenseId) {
        $stmt = $this->db->query("SELECT * FROM devices WHERE license_id = ? AND status = 'active'", [$licenseId]);
        return $stmt->fetchAll();
    }

    public function countDevicesByLicense($licenseId) {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM devices WHERE license_id = ? AND status = 'active'", [$licenseId]);
        $row = $stmt->fetch();
        return (int)$row['count'];
    }

    public function registerDevice($licenseId, $deviceId, $deviceName, $ipAddress) {
        $now = time();
        $this->db->query(
            "INSERT INTO devices (license_id, device_id, device_name, ip_address, status, activated_at, updated_at) 
             VALUES (?, ?, ?, ?, 'active', ?, ?) 
             ON DUPLICATE KEY UPDATE device_name = ?, ip_address = ?, updated_at = ?",
            [$licenseId, $deviceId, $deviceName, $ipAddress, $now, $now, $deviceName, $ipAddress, $now]
        );
    }

    public function removeDevice($licenseId, $deviceId) {
        $this->db->query("DELETE FROM devices WHERE license_id = ? AND device_id = ?", [$licenseId, $deviceId]);
    }

    public function removeDeviceById($id) {
        $this->db->query("DELETE FROM devices WHERE id = ?", [$id]);
    }

    public function renameDevice($id, $newName) {
        $this->db->query("UPDATE devices SET device_name = ?, updated_at = ? WHERE id = ?", [$newName, time(), $id]);
    }

    public function resetLicenseDevices($licenseId) {
        $this->db->query("DELETE FROM devices WHERE license_id = ?", [$licenseId]);
    }
}
