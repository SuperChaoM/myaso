//
//  main.m
//  oc_learn
//
//  Created by mymac on 16/7/12.
//  Copyright © 2016年 mymac. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "NSStudent.h"
#import "ASRectangle1.h"
#import "ASShape.h"
#import "ASRectrangle.h"
#import "ASTriangle.h"
#import "ASCircle.h"
#import "ASPerson.h"
int main(int argc, const char * argv[]) {
    @autoreleasepool {
        
        /* ASDrawimage  多态*/
        ASPerson * person = [[ASPerson alloc] init];
        ASCircle * circle = [[ASCircle alloc] init];
        ASRectrangle * rec = [[ASRectrangle alloc] init];
        ASTriangle * tri = [[ASTriangle alloc] init];
        
        [person paint:circle];
        [person paint:rec];
        [person paint:tri];
        
        [circle release];
        [rec release];
        [tri release];
        
        
        
        
        
        /*
        NSString *xname = [[NSString alloc] initWithFormat:@"aaaaddddd"];
        
        NSLog(@"rec-retainCount1:%lu", xname.retainCount);
        
        NSPeople *people = [[NSPeople alloc] initWithName:xname WithAge:20 WithHeight:177];

        NSLog(@"rec-retainCount2:%lu", people.name.retainCount);
        
        [xname release];
        
        NSLog(@"rec-retainCount3:%lu", xname.retainCount);
        
        NSLog(@"rec-retainCount4:%lu", people.name.retainCount);
        
        
        
        NSStudent *student =[[NSStudent alloc] initWithName:@"myname"];   //分配空间 并初始化,也可写成 ［NSStudent new］，new＝alloc＋init
        
        [people print];
        [student print];
        
        [people release];
        people = student;
        
        [people print];
        NSLog(@"descript:%@", people);  //通过重载 description 方法，可直接打印类

        [people release];
        */
        /*
        NSLog(@"name:%@", [student name]);
        NSLog(@"age:%d", student.age);
        student.name=@"tom";
        [NSStudent print];
        NSLog(@"name2:%@", [student name]);
        
        

        ASRectangle1 *rec = [[ASRectangle1 alloc] init ];
        
        [rec setWidth:5 heigh:6];
        NSLog(@"area:%g  len:%g", [rec area], [rec len]);
        rec.width = 5;
        NSLog(@"width:%g  len:%g", rec.width, [rec heigh]);
        
        NSLog(@"rec-retainCount:%lu", (unsigned long)[rec retainCount]);
        
        [rec retain]; //引用计数加 1
        NSLog(@"rec-retainCount2:%lu", (unsigned long)[rec retainCount]);
        
        [student autorelease];//延迟释放，autoreleasepool 结束时释放

        [rec release];
        [rec release];
        NSLog(@"rec-retainCount-end:%lu", (unsigned long)[rec retainCount]);
    
        */
    }
    
    
    
    return 0;
}
