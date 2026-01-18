<?php
// Field Validation Function
function fieldValidation($regex, $my_string)
{
	if (preg_match($regex, $my_string)) {
		return $my_string;
	} else {
		return false;
	}
} // End function fieldValidation($regex, $my_string)

// Clean String
function clean_string($string)
{
	$bad = array("content-type", "bcc:", "to:", "cc:", "href");
	return str_replace($bad, "", $string);
} // End function clean_string($string)


// Simple file-based rate limiting (best-effort)
// Returns: array('allowed' => bool, 'retryAfter' => int)
function rate_limit_check_and_record($namespaceKey, $maxAttempts = 8, $windowSeconds = 3600, $minIntervalSeconds = 10)
{
	$now = time();
	$maxAttempts = (int) $maxAttempts;
	$windowSeconds = (int) $windowSeconds;
	$minIntervalSeconds = (int) $minIntervalSeconds;

	if ($maxAttempts < 1 || $windowSeconds < 1) {
		return array('allowed' => true, 'retryAfter' => 0);
	}

	$dir = sys_get_temp_dir();
	if (empty($dir)) {
		return array('allowed' => true, 'retryAfter' => 0);
	}

	$keyHash = hash('sha256', (string) $namespaceKey);
	$path = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'fj_contact_rl_' . $keyHash . '.json';

	$fh = @fopen($path, 'c+');
	if ($fh === false) {
		return array('allowed' => true, 'retryAfter' => 0);
	}

	if (!@flock($fh, LOCK_EX)) {
		@fclose($fh);
		return array('allowed' => true, 'retryAfter' => 0);
	}

	$raw = stream_get_contents($fh);
	$data = array('attempts' => array(), 'last' => 0);
	if (!empty($raw)) {
		$decoded = json_decode($raw, true);
		if (is_array($decoded)) {
			$data = array_merge($data, $decoded);
		}
	}

	$attempts = array();
	if (isset($data['attempts']) && is_array($data['attempts'])) {
		$attempts = array_map('intval', $data['attempts']);
	}

	$cutoff = $now - $windowSeconds;
	$attempts = array_values(array_filter($attempts, function ($t) use ($cutoff) {
		return $t >= $cutoff;
	}));

	$last = isset($data['last']) ? (int) $data['last'] : 0;

	$allowed = true;
	$retryAfter = 0;

	if ($minIntervalSeconds > 0 && $last > 0 && ($now - $last) < $minIntervalSeconds) {
		$allowed = false;
		$retryAfter = $minIntervalSeconds - ($now - $last);
	} elseif (count($attempts) >= $maxAttempts) {
		$allowed = false;
		$oldest = min($attempts);
		$retryAfter = max(1, ($oldest + $windowSeconds) - $now);
	} else {
		$attempts[] = $now;
		$last = $now;
	}

	$data = array(
		'attempts' => $attempts,
		'last' => $last
	);

	rewind($fh);
	@ftruncate($fh, 0);
	@fwrite($fh, json_encode($data));
	@fflush($fh);
	@flock($fh, LOCK_UN);
	@fclose($fh);

	return array('allowed' => $allowed, 'retryAfter' => (int) $retryAfter);
}



?>