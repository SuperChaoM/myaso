//
//  ViewController.swift
//  weather
//
//  Created by vmac on 16-6-23.
//  Copyright (c) 2016年 vmac. All rights reserved.
//

import UIKit

class ViewController: UIViewController {
    
    @IBOutlet var tv:UITextView    
    
    @IBAction func btnPress(sender : AnyObject){
        println("btn clicked")
        LoadWeather()
    }
                            
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        //LoadWeather()
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    func LoadWeather() {
        
        var url = NSURL(string:"http://www.weather.com.cn/data/sk/101230101.html")
        
        var dataweather = NSData.dataWithContentsOfURL(url, options: NSDataReadingOptions.DataReadingUncached, error: nil)
        
        var str = NSString(data:dataweather,encoding:NSUTF8StringEncoding)
        
        var json : AnyObject! = NSJSONSerialization.JSONObjectWithData(dataweather, options: NSJSONReadingOptions.AllowFragments, error: nil)
        
        var weatherinfo : AnyObject! = json.objectForKey("weatherinfo")
        
        var city = weatherinfo.objectForKey("city")
        var temp = weatherinfo.objectForKey("temp")
        
        tv.text = "城市：\(city)\n温度：\(temp)\n"
        
    }


}

