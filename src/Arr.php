<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace houdunwang\arr;
/**
 * 数组管理
 * Class Arr
 * @package hdphp\arr
 * @author 向军
 */
class Arr {
	private $app;

	public function __construct( $app ) {
		$this->app = $app;
	}

	/**
	 * 根据键名获取数据
	 * 如果键名不存在时返回默认值
	 *
	 * @param array $data
	 * @param $key
	 * @param null $value
	 *
	 * @return array|mixed|null
	 */
	public function get( array $data, $key, $value = null ) {
		$exp = explode( '.', $key );
		foreach ( (array) $exp as $d ) {
			if ( isset( $data[ $d ] ) ) {
				$data = $data[ $d ];
			} else {
				return $value;
			}
		}

		return $data;
	}

	/**
	 * 设置数组元素值支持点语法
	 *
	 * @param array $data
	 * @param $key
	 * @param $value
	 *
	 * @return array
	 */
	public function set( array $data, $key, $value ) {
		$tmp =& $data;
		foreach ( explode( '.', $key ) as $d ) {
			if ( ! isset( $tmp[ $d ] ) ) {
				$tmp[ $d ] = [ ];
			}
			$tmp = &$tmp[ $d ];
		}
		$tmp = $value;

		return $data;
	}

	/**
	 * 将数组键名变成大写或小写
	 *
	 * @param array $arr 数组
	 * @param int $type 转换方式 1大写   0小写
	 *
	 * @return array
	 */
	public function keyCase( $arr, $type = 0 ) {
		$func = $type ? 'strtoupper' : 'strtolower';
		$data = [ ]; //格式化后的数组
		foreach ( $arr as $k => $v ) {
			$k          = $func( $k );
			$data[ $k ] = is_array( $v ) ? $this->keyCase( $v, $type ) : $v;
		}

		return $data;
	}

	/**
	 * 不区分大小写检测数据键名是否存在
	 *
	 * @param $key
	 * @param $arr
	 *
	 * @return bool
	 */
	public function keyExists( $key, $arr ) {
		return array_key_exists( strtolower( $key ), $this->keyExists( $arr ) );
	}

	/**
	 * 将数组中的值全部转为大写或小写
	 *
	 * @param array $arr
	 * @param int $type 类型 1值大写 0值小写
	 *
	 * @return array
	 */
	public function valueCase( $arr, $type = 0 ) {
		$func = $type ? 'strtoupper' : 'strtolower';
		$data = [ ]; //格式化后的数组
		foreach ( $arr as $k => $v ) {
			$data[ $k ] = is_array( $v ) ? $this->valueCase( $v, $type ) : $func( $v );
		}

		return $data;
	}

	/**
	 * 数组进行整数映射转换
	 *
	 * @param $arr
	 * @param array $map
	 *
	 * @return mixed
	 */
	public function intToString( $arr, array $map = [ 'status' => [ '0' => '禁止', '1' => '启用' ] ] ) {
		foreach ( $map as $name => $m ) {
			if ( isset( $arr[ $name ] ) && array_key_exists( $arr[ $name ], $m ) ) {
				$arr[ '_' . $name ] = $m[ $arr[ $name ] ];
			}
		}

		return $arr;
	}

	/**
	 * 数组中的字符串数字转为INT类型
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function stringToInt( $data ) {
		$tmp = $data;
		foreach ( (array) $tmp as $k => $v ) {
			$tmp[ $k ] = is_array( $v ) ? $this->stringToInt( $v ) : ( is_numeric( $v ) ? intval( $v ) : $v );
		}

		return $tmp;
	}

	/**
	 * 根据下标过滤数据元素
	 *
	 * @param array $data 原数组数据
	 * @param $keys 参数的下标
	 * @param int $type 1 存在在$keys时过滤  0 不在时过滤
	 *
	 * @return array
	 */
	public function filterKeys( array $data, $keys, $type = 1 ) {
		$tmp = $data;
		foreach ( $data as $k => $v ) {
			if ( $type == 1 ) {
				//存在时过滤
				if ( in_array( $k, $keys ) ) {
					unset( $tmp[ $k ] );
				}
			} else {
				//不在时过滤
				if ( ! in_array( $k, $keys ) ) {
					unset( $tmp[ $k ] );
				}
			}
		}

		return $tmp;
	}
}