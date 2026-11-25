<?php
header('Content-Type: text/plain; charset=utf-8');
echo "Running git checkout...\n";
$output1 = shell_exec("git checkout -- views/ 2>&1");
$output2 = shell_exec("git checkout -- controllers/ 2>&1");
echo "Views output: " . $output1 . "\n";
echo "Controllers output: " . $output2 . "\n";
echo "Done!\n";
unlink(__FILE__); // Self-delete
