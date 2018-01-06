<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use App\Models\Cats;
use DB;

class CatsPandC extends Model {

    protected $table = 'cats_p_and_c';
    protected $fillable = ['parent_id', 'child_id'];


    public function saveParentChild($parentIdArr, $childId, $deleteParentIdArr)
    {

        DB::beginTransaction();

        // delete existing parent-child relationships in table
        DB::table('cats_p_and_c')->where('child_id', $childId)->delete();

        // save new parent-child relationships
        if (count($parentIdArr) > 0) {
            $valuesArr = array();
            $parentIdArr = array_unique($parentIdArr);
            foreach($parentIdArr as $parentId) {
                if (in_array($parentId, $deleteParentIdArr)) {
                    continue;
                }
                $valuesArr[] = array('child_id' => $childId, 'parent_id' => $parentId);
            }
            if (count($valuesArr) > 0) {
                DB::table('cats_p_and_c')->insert($valuesArr);
            }
        }

        DB::commit();

    }

    /*
     * Get parent ids associated with child_id
     */
    public function getSelectedParentIdNameArr($child_id)
    {
        return $this->select(array('cats.title as title', 'cats.id as parent_id'))
            ->join('cats', 'cats.id', '=', 'cats_p_and_c.parent_id')
            ->where('cats_p_and_c.child_id', '=', $child_id)
            ->lists('title', 'parent_id');
    }

    /**
     * Make array of id=>name for select drop down menu for associating a category with a parent
     * Don't allow the current category id be selectable as a parent
     * Don't allow already selected parent id selectable in dropdowns except for the drop down it is set as selected in
     */
    public function makeDDArr($parentIdNameArr, $selectedParentIdNameArr, $currentId)
    {

        $ddArr = array(0 => ' - none -');
        foreach($parentIdNameArr as $id => $name) {
            if ($currentId != $id && !isset($selectedParentIdNameArr[$id])) {
                $ddArr[$id] = $name;
            }
        }

        return $ddArr;

    }

    private function buildTree(array $elements, $parentId = 0)
    {

        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['child_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;

    }

    /*
     * The top level parent only categories must have an entry in cats_p_and_c table that has a parent_id of 0
     *
     * Retrieves hierarchy of parent-child category relationships
     *
     * Returns an array in format:
     * Array(
            [0] => Array
                (
                    [parent_id] => 0
                    [child_id] => 18
                    [children] => Array
                        (
                            [0] => Array
                                (
                                    [parent_id] => 18
                                    [child_id] => 49
    */
    public function getHierarchy()
    {

        $parentChildArr = array();
        //$categoryModel = new Cats();
        //$parentArr = $categoryModel->getParents();
        //foreach($parentArr as $id => $name) {
            //$parentChildArr[] = array('parent_id' => 0, 'child_id' => $id);
        //}

        $categoriesParentAndChildrenArr = $this->all();
        foreach($categoriesParentAndChildrenArr as $key => $obj) {
            $tmp = $obj->getAttributes();
            $parentChildArr[] = array('parent_id' => $tmp['parent_id'], 'child_id' => $tmp['child_id']);
        }

        $tree = $this->buildTree($parentChildArr);

        return $tree;

    }

    public function getParentChildArr() {

        $coll = $this->all();
        $parentChildArr = array();
        foreach($coll as $obj) {
            if (!isset($parentChildArr[$obj->parent_id])) {
                $parentChildArr[$obj->parent_id] = [];
            }
            $parentChildArr[$obj->parent_id][] = $obj->child_id;

        }

        return $parentChildArr;

    }

    public function flattenHier($catsColl) {

        $parentChildHierArr = $this->getHierarchy();
        $x = [];
        foreach($parentChildHierArr as $key => $arr) {

            /*
            $arr
            Array (
                [parent_id] => 0
                [child_id] => 7
                [children] => Array
                (
                    [0] => Array
                    (
                    [parent_id] => 7
                    [child_id] => 8
                    [children] => Array
                        (
                            [0] => Array
            */
            $childId = isset($arr['child_id']) ? $arr['child_id'] : 0;
            $tmp = $this->flatten($arr, [], $catsColl);
            $x[$childId] = $tmp;

        }

        return $x;

    }

    private function flatten($arr, $x, $catsColl) {

        if (isset($arr['children'])) {
            foreach($arr['children'] as $i => $tmp) {
                $childId = $tmp['child_id'];
                $val = $this->flatten($tmp, [], $catsColl);
                $x[$childId] = $val;

            }
            return $x;
        } else {
            $childId = $arr['child_id'];
            return $childId;
        }


    }

}
