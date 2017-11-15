<?php
function cleanMac($macAddress) {
        return strtoupper(str_replace(":", "", $macAddress));
}
?>
