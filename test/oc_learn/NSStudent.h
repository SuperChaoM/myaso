//
//  NSStudent.h
//  oc_learn
//
//  Created by mymac on 16/7/18.
//  Copyright © 2016年 mymac. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "NSPeople.h"

@interface NSStudent : NSPeople{
    NSString *sid;
}
-(id) initWithName:(NSString*)aName;

@property(nonatomic, readwrite, retain) NSString *sid;

@end
