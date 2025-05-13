<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$flashTypes = [
  "flash_success" => ["success", "✅"],
  "flash_error"   => ["error", "❌"],
  "flash_info"    => ["info", "ℹ️"],
  "flash_warning" => ["warning", "⚠️"]
];

echo "<div class='flash-container'>";
foreach ($flashTypes as $key => [$idSuffix, $icon]) {
  if (isset($_SESSION[$key])) {
    $messages = is_array($_SESSION[$key]) ? $_SESSION[$key] : [$_SESSION[$key]];
    foreach ($messages as $msg) {
      echo "<div id='flash-{$idSuffix}' class='flash-message flash-{$idSuffix}'>
              <span class='icon'>{$icon}</span>
              <span class='msg-text'>{$msg}</span>
              <span class='close-btn'>&times;</span>
            </div>";
    }
    unset($_SESSION[$key]);
  }
}
echo "</div>";
?>


