<?php
require_once '../vendor/autoload.php';

use function task1\getTree;

$tree = getTree(1);

?>

<pre>
<?php
print_r ($tree);
?>
</pre>
