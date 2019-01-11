<?php
$baseParameters = require __DIR__ . '/params.php';


// -- For info used by multiple tests
$parameters = array_merge($baseParameters, [
    'oauth-client_id' => 'viterra-id',
    'oauth-client_secret' => 'viterra-secret',
    'login-access_token' => '0b21eb6bf695bc69fec4d21820a1e52f6584b0c7',
    'login-refresh_token' => 'f93ac24cdfd019d653869c591654f8157e4a43a2',
    'login-name' => 'Tester',
    'login-mobileDeviceId' => 'test-mobile-1',
    'login-password' => '123-tester-password=123',
    'login-passwordHash' => '$2y$13$3rXTQSXSLX8lOjjkyNPYm.I63AbhY8SOWBsAp6tdgXW//FP4uNBFm',
    'login-deviceId' => '5c32ec1a52c0ce10be3c0dd3',
    'firebase-instance-id-token' => 'dsfpdPhtWBI:APA91bFCR-NzCJXpmmfI-9y9LNIiVu4QxN7shd8TVz1vj3he8nIgPJQ1PKlxlqxogowevuJb5F8j7LPfULOdkiBp5nwIKRwevOZ1v5FQJ-JBB5N1eM5WkeTTEH1yOXoK476y6gIYKMGp'
]);


return $parameters;
