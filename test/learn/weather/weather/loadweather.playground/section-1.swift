// Playground - noun: a place where people can play

import UIKit

var url = NSURL(string:"http://www.weather.com.cn/data/sk/101230101.html")

var dataweather = NSData.dataWithContentsOfURL(url, options: NSDataReadingOptions.DataReadingUncached, error: nil)

var str = NSString(data:dataweather,encoding:NSUTF8StringEncoding)

var json : AnyObject! = NSJSONSerialization.JSONObjectWithData(dataweather, options: NSJSONReadingOptions.AllowFragments, error: nil)

var weatherinfo : AnyObject! = json.objectForKey("weatherinfo")

var city = weatherinfo.objectForKey("city")
var temp = weatherinfo.objectForKey("temp")

