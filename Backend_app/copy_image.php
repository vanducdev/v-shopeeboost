<?php
$src = "C:/Users/ducva/.gemini/antigravity-ide/brain/31f5ae0c-bcd7-45b2-96b0-f71ad2b6fb5e/vmax_box_mockup_1782182306970.png";
$dest = __DIR__ . "/image/vmax_box_mockup.png";
if (copy($src, $dest)) {
    echo "SUCCESS";
} else {
    echo "FAILED";
}
unlink(__FILE__);
