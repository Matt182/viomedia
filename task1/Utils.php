<?php
namespace task1;

use task1\DbTreeActions;

/** @param int $id @return array */
function getTree($id)
{
    $db = new DbTreeActions();
    $nodes = $db->getChildrenNodes($id);
    return buildTree($nodes);
}

/** @param array $nodes @param int $parentId @return array */
function buildTree(array &$nodes, $parentId = 0) {

    $branch = [];

    foreach ($nodes as &$node) {

        if ($node['parent'] == $parentId) {
            $children = buildTree($nodes, $node['id']);
            if ($children) {
                $node['children'] = $children;
            }
            $branch[$node['id']] = $node;
            unset($node);
        }
    }
    return $branch;
}
