<?php
/**
* @package Ambitionality
* @copyright Copyright (C) 2008 Ambitionality Software LLC. All rights reserved.
* @license GNU/GPL
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
class AmbitionalityHelperTree
{
    var $_root;
    var $_idField;
    var $_parentIdField;
    var $_allNodes;

    function AmbitionalityHelperTree($items, $idField, $parentIdField)
    {
        $this->_idField = $idField;
        $this->_parentIdField = $parentIdField;

        $this->_initialize($items);
    } //end constructor

    function _initialize($items)
    {
        $this->_root = new AmbitionalityHelperTreeNode();

        $this->_allNodes = array();
        foreach ($items as $item) {
            $node = new AmbitionalityHelperTreeNode($item);

            $this->_allNodes[$item->{$this->_idField}] = $node;

            if (!$item->{$this->_parentIdField}) {
                $this->_root->addChild($node);
            }
        }

        foreach ($this->_allNodes as $node) {
            if ($node->getObject()->{$this->_parentIdField}) {
                if (isset($this->_allNodes[$node->getObject()->{$this->_parentIdField}])) {
                    $this->_allNodes[$node->getObject()->{$this->_parentIdField}]->addChild($node);
                } else {
                    $node->setOrphan(true);
                    $this->_root->addChild($node);
                }
            }
        }

        $this->traverse(array($this, '_calculateDepth'), $this->_root);
    } //end _initialize

    function traverse($callback, $root = null, $preorder = true, $callbackArgs = array())
    {
        if (!$root) {
            $root = $this->_root;
        }

        if ($preorder) {
            call_user_func_array($callback, array_merge(array($root), $callbackArgs));
        }

        foreach ($root->getChildren() as $child) {
            $this->traverse($callback, $child, $preorder, $callbackArgs);
        }

        if (!$preorder) {
            call_user_func_array($callback, array_merge(array($root), $callbackArgs));
        }
    } //end traverse

    function _calculateDepth(&$node)
    {
        if ($node->getParent()) {
            $node->setDepth($node->getParent()->getDepth() + 1);
        } else {
            $node->setDepth(0);
        }
    } //end _calculateDepth

    function getNodes($preorder = true)
    {
        $list = array();

        $this->traverse(array($this, '_getNodes'), $this->_root, true, array(&$list));

        return $list;
    } //end getNodes

    function getItems($preorder = true)
    {
        $list = array();

        $this->traverse(array($this, '_getItems'), $this->_root, true, array(&$list));

        return $list;
    } //end getNodes

    function _getItems(&$node, &$list)
    {
        if ($node->getObject()) {
            $list[] = &$node->getObject();
        }
    } //end _getItems

    function _getNodes(&$node, &$list)
    {
        if ($node->getObject()) {
            $list[] = &$node;
        }
    } //end _getNodes

    function getTopLevelNode($tableName, $id, $idField = 'id', $parentIdField = 'parentId', $db = null)
    {
        if (!$db) {
            $db = &JFactory::getDBO();
        }

        // get parent node
        $query = 'SELECT a.'.$parentIdField.'
                  FROM `'.$tableName.'` AS a
                  WHERE a.'.$idField.' = '.(int)$id
        ;
        $db->setQuery($query);
        $parentId = $db->loadResult();

        if ($parentId) {
            return AmbitionalityHelperTree::getTopLevelNode($tableName, $parentId, $idField, $parentIdField, $db);
        }

        return $id;
    } //end getTopLevelNode

    function getAllChildren($tableName, $id, &$ids, $idField = 'id', $parentIdField = 'parentId', $db = null)
    {
        if (!$db) {
            $db = &JFactory::getDBO();
        }

        $ids[] = $id;

        $query = 'SELECT a.id
                         FROM `'.$tableName.'` AS a
                         WHERE a.'.$parentIdField.' = '.(int)$id
        ;
        $db->setQuery($query);
        $childIds = $db->loadResultArray();

        foreach ($childIds as $childId) {
            AmbitionalityHelperTree::getAllChildren($tableName, $childId, $ids, $idField, $parentIdField, $db);
        }
    } //end getAllChildren

} //end AmbitionalityHelperTree

class AmbitionalityHelperTreeNode
{
    var $_children;
    var $_object;
    var $_parent;
    var $_depth;

    function AmbitionalityHelperTreeNode($object = null)
    {
        $this->_depth = 0;
        $this->_parent = null;
        $this->_object = $object;
        $this->_children = array();
    } //end __construct

    function addChild(&$child)
    {
        $child->_parent = &$this;
        $this->_children[] = $child;
    } //end addChild

    function getChildren()
    {
        return $this->_children;
    } //end getChildren

    function getObject()
    {
        return $this->_object;
    } //end getObject

    function getParent()
    {
        return $this->_parent;
    } //end getParent

    function getDepth()
    {
        return $this->_depth;
    } //end getDepth
    
    function setDepth($depth)
    {
        $this->_depth = $depth;
    } //end setDepth

    function setOrphan($orphan)
    {
        $this->_orphan = $orphan;
    } //end setOrphan

    function isOrphan()
    {
        return $this->_orphan;
    } //end isOrphan

    function sortChildren($callback)
    {
        usort($this->_children, $callback);
    } //end sortChildren

} //end AmbitionalityHelperTreeNode
?>
