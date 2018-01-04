<?php
/**
 * Created by PhpStorm.
 * User: Geridge
 * Date: 2016/11/17
 * Time: 14:55
 */

namespace common\helpers;

use yii\base\Object;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
/**
 * 助手。
 * Class ConstantHelper
 * @package common\helpers
 */
class Helper
{
    /**
     * （调用外部接口）
     * @param $url
     * @param $data
     * @return mixed
     */
    public static function curl($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        if (!empty($data)) {
            curl_setopt ( $ch, CURLOPT_POST, 1 ); //启用POST提交
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $file_contents = curl_exec($ch);
        curl_close($ch);

        return $file_contents;
    }
    
    /**
     * 数据网格分页参数
     * @param  $post
     * @return array
     */
    public static function paganitionFilter($post)
    {
        $page = isset($post['page']) ? intval($post['page']) : 1;
        $rows = isset($post['rows']) ? intval($post['rows']) : 10;
        $offset = ($page-1)*$rows;
        $post['limit_sql'] = " limit $offset,$rows";
        $post['offset'] = $offset;
        $post['limit'] = $rows;
        //在此处处理时间排序的变量
        if (isset($post['sort'])){
            switch ($post['sort']){
                case "created_time":
                    $post['sort'] = "created_at";
                    break;
                case "updated_time":
                    $post['sort'] = "updated_at";
                    break;
            }
        }
        //yii2的sql语句封装的order是yii2_order,此处转变一下
        if (isset($post['order'])){
            switch ($post['order']){
                case "asc":
                    $post['yii2_order'] = "SORT_ASC";
                    break;
                case "desc":
                    $post['yii2_order'] = "SORT_DESC";
                    break;
            }
        }else{
            $post['yii2_order'] = "SORT_ASC";
        }
        $post['sort'] = isset($post['sort']) ? $post['sort'] : 'id';
        $post['order'] = isset($post['order']) ? $post['order'] : 'asc';
        
        return $post;
    }
    
    /**
     * 将数组里的unix时间戳变为正常时间
     * @param $res array
     * @return array
     */
    public static function UnixTimeConversion($res)
    {
        foreach ($res as $k => $v){
            if (isset($v['created_at'])){
                $res[$k]['created_time'] = date('Y-m-d',$v['created_at']);
            }
            if (isset($v['deadline_at'])){
                $res[$k]['deadline_time'] = date('Y-m-d',$v['deadline_at']);
            }
            if (isset($v['updated_at'])){
                $res[$k]['updated_time'] = date('Y-m-d',$v['updated_at']);
            }
            if (isset($v['start_at'])){
                $res[$k]['start_at'] = date('Y-m-d H:i:s',$v['start_at']);
            }
            if (isset($v['end_at'])){
                $res[$k]['end_at'] = date('Y-m-d H:i:s',$v['end_at']);
            }
            //-------------------------
            if (isset($v['manufacture_date'])){
                $res[$k]['manufacture_date'] = date('Y-m-d',$v['manufacture_date']);
            }
            if (isset($v['effective_date'])){
                $res[$k]['effective_date'] = date('Y-m-d',$v['effective_date']);
            }
            if (isset($v['last_checkout_date'])){
                $res[$k]['last_checkout_date'] = date('Y-m-d',$v['last_checkout_date']);
            }
            if (isset($v['next_checkout_date'])){
                $res[$k]['next_checkout_date'] = date('Y-m-d',$v['next_checkout_date']);
            }
        }
        return $res;
    }
    
    /**
     * 一维数组去空
     * @param $arr
     * @return array
     */
    public static function ArrRremoveEmpty($arr)
    {
        $new_arr = array();
        foreach ($arr as $k => $v){
            if (!empty($v)){
                $new_arr[$k] = $arr[$k];
            }
        }
        return $new_arr;
    }
	
    /**
     * word文档生成html
     * @param $filename 文件路径
     * @return boolean
     */
    public static function WordToHtml($file_path)
    {
        $file_path = preg_replace("/\//", "\\", $file_path);
        $file_path = preg_replace("/common\\\helpers/" , "backend\\web\\" , dirname(__FILE__)) . $file_path;
        $script_path = dirname(__FILE__) . '\word.py ';
        $command = 'python ' . $script_path . $file_path;
        $res = exec($command, $res);
        $file_arr = explode('\\',$res);
        $html_path = 'source/file/' . $file_arr[count($file_arr) - 2] . '/' . $file_arr[count($file_arr) - 1];
        return $html_path;
    }
    
    /*
     * 数据网格的返回结果封装
     * @param $data 数据集
     * @return array
     * 
     */
    public function DataGridReturn($data) 
    {
        $res['rows'] = $data;
        $res['total'] = count($data);
        return json_encode($res);
    }
    
    /*
     * sql语句封装
     * @param $sql sql语句
     * @return array
     *
     */
    public function SqlReturn($sql)
    {
        return $sql . ' order by :sort :order limit :offset,:limit';
    }
    
    /*
     * sql语句参数封装
     * @param $post 参数
     * @return array
     *
     */
    public function SqlParamReturn($post)
    {
        $page_arr[':sort'] = $post['sort'];
        $page_arr[':order'] = $post['order'];
        $page_arr[':offset'] = $post['offset'];
        $page_arr[':limit'] = $post['limit'];
        return $page_arr;
    }
}