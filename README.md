<h1 align="center"> district </h1>

<p align="center"> A district SDK. 友好支持Laravel框架</p>


## 安装

```shell
$ composer require wdy/district -vvv
```

## 配置

在使用本扩展之前，你需要去 高德开放平台 注册账号，然后创建应用，获取应用的 API Key。

## 用法

```php
use Wdy\District\District;

$key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';

$d = new District($key);

$response = $d->getDistrict('成都');

```

结果示例:
```json
{
    "status": "1",
    "info": "OK",
    "infocode": "10000",
    "count": "1",
    "suggestion": {
        "keywords": [],
        "cities": []
    },
    "districts": [
        {
            "citycode": "028",
            "adcode": "510100",
            "name": "成都市",
            "center": "104.065735,30.659462",
            "level": "city",
            "districts": []
        }
    ]
}
```
参数说明:
```php
getDistrict(string $keywords, int $subdistrict = 0, string $output = 'JSON')
```
$subdistrict参数设置显示下级行政区级数,可选值：0、1、2、3;
$output参数设置返回数据格式类型,可选值:JSON、XML

laravel用法:

```php
//容器获取方式
app('district')->getDistrict('成都');

//依赖注入方式
public function __construct(District $district)
{
    $this->district = $district;
}
```

## 鸣谢

[overtrue](https://github.com/overtrue)

## License

MIT