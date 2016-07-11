<?php
require_once '../vendor/autoload.php';

use task2\DB;
use task2\User;

$user = User::getInstance(1);
$user->set('home/street', ['bld' => 'saf']);
?>
<pre>
<?php print_r($user->get()); ?>
</pre>
