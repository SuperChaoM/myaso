//
//  ViewController.swift
//  calculator
//
//  Created by vmac on 16-6-26.
//  Copyright (c) 2016 vmac. All rights reserved.
//

import UIKit

class ViewController: UIViewController {
    
    //property     name      type
    @IBOutlet var dispaly:UILabel?
    
    var usrIsInTheMiddTyping:Bool = false
    
    var brain = CalculatorBrain()
    

    @IBAction func appendDiGit(sender: UIButton) {
        let digit = sender.currentTitle
        print("\(digit) clicked")
        
        if usrIsInTheMiddTyping {
            dispaly!.text = dispaly!.text! + digit!
        } else {
            dispaly!.text = digit
            usrIsInTheMiddTyping = true
        }
    }
    
    @IBAction func oper(sender: UIButton) {
        //auto enter
        if usrIsInTheMiddTyping {
            enter()
        }
        
        print("\(oper) clicked")
        
        if let operand = sender.currentTitle {
            if let result = brain.performOperation(operand) {
                displayValue = result
            } else {
                displayValue = 0
            }
            
        }
    }

    
    var displayValue:Double{
        get {
            return NSNumberFormatter().numberFromString(dispaly!.text!)!.doubleValue
        }
    
        set {
            dispaly!.text = "\(newValue)"
            usrIsInTheMiddTyping = false
        }
    }
    
    @IBAction func enter() {
        usrIsInTheMiddTyping = false
        if let result = brain.pushOperand(displayValue) {
            displayValue = result
        } else {
            displayValue = 0
        }
    }
    
}

