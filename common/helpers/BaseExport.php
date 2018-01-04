<?php
/**
 * Created by ice.leng(lengbin@geridge.com)
 * Date: 2016/1/27
 * Time: 15:55
 */

namespace common\helpers;


interface BaseExport
{

    public function load($data, $offset=0);

    public function export();
}