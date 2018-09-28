<?php
class Email {
    const WAIT_CHECK = 1;
    const CHECKING = 2;
    const CHECK_DONE = 3;

    static $status_name = [
        self::WAIT_CHECK => '等待过检',
        self::CHECKING => '正在过检',
        self::CHECK_DONE => '已过检',
    ];
}

class AccountStatus {
    const NORMAL = 1;
    const FORBIDDEN = 2;

    static $status_name = [
        self::NORMAL => '正常',
        self::FORBIDDEN => '被封禁',
    ];
}

class WorkStatus {
    const WAIT_RUN = 1;
    const RUNNING = 2;
    const SUCC = 3;
    const HANDLE = 4;

    static $status_name = [
        self::WAIT_RUN => '等待执行',
        self::RUNNING => '正在执行',
        self::SUCC =>'执行成功',
        self::HANDLE => '手工停止'
    ];
}

class TaskStatus {
    const WAIT = -1;
    const WAIT_RUN = 1;
    const Fail = 2;
    const SUCC = 3;
    static $status_name = [
        self::WAIT_RUN => '等待执行',
        self::Fail => '失败',
        self::SUCC =>'成功',
    ];
}

class AppleShop {
    public static $city = [
        '北京北京市',
        '上海上海市',
        '天津天津市',
        '重庆重庆市',
        '安徽合肥市',
        '福建福州市',
        '甘肃兰州市',
        '广东广州市',
        '广西南宁市',
        '贵州贵阳市',
        '海南海口市',
        '河北石家庄市',
        '河南郑州市',
        '黑龙江哈尔滨市',
        '湖北武汉市',
        '湖南长沙市',
        '吉林长春市',
        '江苏南京市',
        '江西南昌市',
        '辽宁沈阳市',
        '内蒙古呼和浩特市',
        '宁夏银川市',
        '青海西宁市',
        '山东济南市',
        '山西太原市',
        '陕西西安市',
        '四川成都市',
        '西藏拉萨市',
        '新疆乌鲁木齐市',
        '云南昆明市',
        '浙江杭州市',
        '香港九龙',
        '澳门澳门半岛',
        '台湾台北市'
    ];
    public static $address = [
        '西安路###号',
        '联东路###号',
        '环岛路###号',
    ];

    public static function get_data() {
        $data = [
            'city' => self::$city[mt_rand(0, count(self::$city)-1)],
            'address' =>  str_replace('###', mt_rand(1,1000), self::$address[mt_rand(0, count(self::$address)-1)]),
            'phone' => mt_rand(1000000, 9000000),
        ];
        return $data;
    }
}