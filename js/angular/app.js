var app = angular.module('myApp',['ngRoute','functionService','ngSanitize','ui.select','ui.bootstrap','dualmultiselect','angularUtils.directives.dirPagination'])
.run(function($rootScope) {
    $rootScope.userlogged="";
    $rootScope.preloaderText = 'Please wait. Loading records...';

    $rootScope.UserHasAccess = function (str) {
      for(i=0;i<$rootScope.userlogged['accesscodes'].length;i++){
        if($rootScope.userlogged['accesscodes'][i].modulecode==str){
          return true;
        }
      } 
      return false;
    }

    $rootScope.convertToDateTime = function (str) {
      var date = new Date(str),
          mnth = ("0" + (date.getMonth()+1)).slice(-2),
          day  = ("0" + date.getDate()).slice(-2),
          hour  = ("0" + date.getHours()).slice(-2),
          minute  = ("0" + date.getMinutes()).slice(-2),
          secs  = ("0" + date.getSeconds()).slice(-2);
      return [date.getFullYear(), mnth, day].join("-") + ' ' + [hour, minute, secs].join(":");
    }

    $rootScope.convertToDate = function (str) {
      var date = new Date(str),

          mnth = ("0" + (date.getMonth()+1)).slice(-2),
          day  = ("0" + date.getDate()).slice(-2);
      return [date.getFullYear(), mnth, day ].join("-");
    }

    $rootScope.newDate = function (str) {
      if(!str) str = new Date();
      var ndate = new Date(str).toLocaleString("en-US", {timeZone: "Asia/Shanghai"});
      ndate = new Date(ndate);
      return ndate;
    }

    $rootScope.DateDiff = function (str,type='1') {
      var datenow = new Date();
      var date = new Date(str);
      var strday = '',
          strhour = '',
          strmin = 'minute';

      var timeDiff = Math.abs(datenow - date);
      var diffDays = Math.floor(timeDiff/(1000*60*60)); 
      var dday = Math.floor(diffDays/24);
      var dhour = Math.floor(diffDays - (dday * 24));
      var dmin = Math.floor(((timeDiff - (diffDays*(1000*60*60)))/1000)/60);

      if(dday>1)
        strday = dday + ' days';
      else if(dday==1)
        strday = dday + ' day';


      if(dhour>1)
          strhour = ' ' + dhour + ' hours';
      else if(dhour==1)
          strhour = ' ' + dhour + ' hour';
      else
        strhour = '';


      if(dmin>1)
        if(dhour>=1)
          strmin = ' ' + dmin + ' minutes';
        else
          strmin = ' ' + dmin + ' minutes';
      else
        if(dday<1&&strhour<1)
          strmin = 'just now';
        else
          strmin = ''

      if(type=='1'){
        return strday + strhour + strmin;
      }
      else{
        return timeDiff;
      }
      
    }

    $rootScope.convertToDate2 = function (str,deli,type='1') {
      var date = new Date(str),
          mnth = ("0" + (date.getMonth()+1)).slice(-2),
          day  = ("0" + date.getDate()).slice(-2);
          if(type==0){
              return [mnth, day, date.getFullYear()].join(deli);
          }
          else{
              return [date.getFullYear(), mnth, day].join(deli);
          }
    }

    $rootScope.ConvertLeaveDate = function (str) {
      var date = new Date(str),
          mnth = ("0" + (date.getMonth()+1)).slice(-2),
          day  = ("0" + date.getDate()).slice(-2);

      return [$rootScope.getMonthAbr(mnth,false), day, ("0" + date.getFullYear()).slice(-2)].join("-");
    }

    $rootScope.getMonthAbr = function(monthNumber,isFull){
      var monthNames = [ 'January', 'February', 'March', 'April', 'May', 'June',
          'July', 'August', 'September', 'October', 'November', 'December' ];
      var monthabr = [ 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN',
          'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC' ];

      if(isFull)
        return monthNames[monthNumber - 1];
      else
        return monthabr[monthNumber - 1];

    }

    $rootScope.ConvertLeaveDateToDate = function(strDate,deli='-'){
      var thisstr = strDate;

      var strArr;
      strArr = thisstr.split('-');
      var strmonth ='',
          stryear = '20' + strArr[2],
          strday = strArr[1];

      switch (strArr[0].toLowerCase()) {
            case "jan":  strmonth = "01";
                         break;
            case "feb":  strmonth = "02";
                         break;
            case "mar":  strmonth = "03";
                         break;
            case "apr":  strmonth = "04";
                         break;
            case "may":  strmonth = "05";
                         break;
            case "jun":  strmonth = "06";
                         break;
            case "jul":  strmonth = "07";
                         break;
            case "aug":  strmonth = "08";
                         break;
            case "sep":  strmonth = "09";
                         break;
            case "oct":  strmonth = "10";
                         break;
            case "nov":  strmonth = "11";
                         break;
            case "dec":  strmonth = "12";
                         break;
            default:     strmonth = "00";
                         break;
      }

      return [stryear, strmonth, strday].join(deli);

    }

    $rootScope.loadPreloader = function(){
        $rootScope.preloader = true;
        $rootScope.mainContent = false;
      }
    $rootScope.removePreloader = function(){
        $rootScope.preloader = false;
        $rootScope.mainContent = true;
      }

    $rootScope.isSelected = function(x){
      $rootScope.preslctd = x;
    }

    $rootScope.EmitEvent = function(desc) {
        $rootScope.$emit(desc);
    };

    $rootScope.listview = function(x){
      if(x==0){
        $rootScope.applist = true;
      }
      else{
        $rootScope.applist = false;
      }
    }

    $rootScope.AdminInput = function(){
      $('.form-control').focus(function () {
          $(this).parent().addClass('focused');
      });

      //On focusout event
      $('.form-control').focusout(function () {
          var $this = $(this);
          if ($this.parents('.form-group').hasClass('form-float')) {
              if ($this.val() == '') { $this.parents('.form-line').removeClass('focused'); }
          }
          else {
              $this.parents('.form-line').removeClass('focused');
          }
      });

      //On label click
      $('body').on('click', '.form-float .form-line .form-label', function () {
          $(this).parent().find('input').focus();
      });

      //Not blank form
      $('.form-control').each(function () {
          if ($(this).val() !== '') {
              $(this).parents('.form-line').addClass('focused');
          }
      });

    }
        
})

var n = Date.now();

app.config(function ($routeProvider) {
    $routeProvider
        // .when('/', {
        //     templateUrl: 'application/views/content/home.php',
        //     controller : "homeCtrl",
        // })
        // .when('/home', {
        //     templateUrl: 'application/views/content/home.php',
        //     controller : "homeCtrl",
        // })

        //// FDESPI /////
        .when('/order', {
            templateUrl: 'application/views/content/store/ordertrans.php?v='+n,
            controller : "orderCtrl"
        })
        .when('/inventory', {
            templateUrl: 'application/views/content/store/inventory.php?v='+n,
            controller : "inventoryCtrl"
        })
        .when('/usermanagement', {
            templateUrl: 'application/views/content/store/usermanagement.php?v='+n,
            controller : "usermanagementCtrl"
        })
        .when('/receive', {
            templateUrl: 'application/views/content/store/productreceive.php?v='+n,
            controller : "receiveCtrl"
        })

        
        //Maintenance 
        .when('/mntnc/brand', {
            templateUrl: 'application/views/content/store/mntnc/brand.php?v='+n,
            controller : "mntncCtrl"
        })
        .when('/mntnc/category', {
            templateUrl: 'application/views/content/store/mntnc/category.php?v='+n,
            controller : "mntncCtrl"
        })
        .when('/mntnc/model', {
            templateUrl: 'application/views/content/store/mntnc/model.php?v='+n,
            controller : "mntncCtrl"
        })
        .when('/mntnc/type', {
            templateUrl: 'application/views/content/store/mntnc/type.php?v='+n,
            controller : "mntncCtrl"
        })
        .when('/mntnc/unit', {
            templateUrl: 'application/views/content/store/mntnc/unit.php?v='+n,
            controller : "mntncCtrl"
        })

        //reports
        .when('/report/sales', {
            templateUrl: 'application/views/content/store/report/sales.php?v='+n,
            controller : "rep_salesCtrl"
        })


        .when('/leaveapplication/:Param', {
            templateUrl: 'application/views/content/lms/leaveapply.php?v='+n,
            controller : "usermanagementCtrl"
        })
        .when('/applications', {
            templateUrl: 'application/views/content/lms/leaveapplicationlist.php?v='+n,
            controller : "leaveapplicationlistCtrl"
        })
        .when('/employees', {
            templateUrl: 'application/views/content/lms/employeelist.php?v='+n,
            controller : "employeelistCtrl"
        })
        .when('/balances', {
            templateUrl: 'application/views/content/lms/creditlist.php?v='+n,
            controller : "creditlistCtrl"
        })
        .when('/forcedleave', {
            templateUrl: 'application/views/content/lms/forcedleavelist.php?v='+n,
            controller : "FLlistCtrl"
        })

        //user management
        .when('/lmsusermanagement', {
            templateUrl: 'application/views/content/lms/mngmnt/lmsusermanagement.php?v='+n,
            controller : "lmsusermanagementCtrl"
        })

        //reports
        .when('/statushistory', {
            templateUrl: 'application/views/content/lms/report/rep_stathistory.php?v='+n,
            controller : "stathistoryCtrl"
        })
        .when('/repmonet', {
            templateUrl: 'application/views/content/lms/report/rep_monetization.php?v='+n,
            controller : "repmonetCtrl"
        })
        .when('/repwopae', {
            templateUrl: 'application/views/content/lms/report/rep_wopadjustingentry.php?v='+n,
            controller : "adjustingEntryCtrl"
        })

        //Maintenance 
        .when('/leavetype', {
            templateUrl: 'application/views/content/lms/mntnc/mntnc_leavetype.php?v='+n,
            controller : "leavetypeCtrl"
        })
        .when('/signatory', {
            templateUrl: 'application/views/content/lms/mntnc/mntnc_signatory.php?v='+n,
            controller : "signatoryCtrl"
        })
        // END of LMS //

        //HRIS
        //PDS
        .when('/employeepds/:Param', {
            templateUrl: 'application/views/content/employee/employeepds.php?v='+n,
            controller : "employeeCtrl"
        })
        .when('/pdslist', {
            templateUrl: 'application/views/content/employee/employeepdslist.php?v='+n,
            controller : "employeepdslistCtrl"
        })

        //IPCR
        .when('/ipcr/new', {
            templateUrl: 'application/views/content/employee/newipcr.php?v='+n,
            controller : "employeeIPCRnewCtrl"
        })
        .when('/ipcr/:Param', {
            templateUrl: 'application/views/content/employee/employeeipcr.php?v='+n,
            controller : "employeeIPCRCtrl"
        })
        .when('/ipcr', {
            templateUrl: 'application/views/content/employee/employeeipcrlist.php?v='+n,
            controller : "employeeipcrlistCtrl"
        })

        //TRAINING
        .when('/training/facilitator', {
            templateUrl: 'application/views/content/employee/training/facilitator.php?v='+n,
            controller : "train_facilitatorCtrl"
        })
        .when('/training/provider', {
            templateUrl: 'application/views/content/employee/employeeipcr.php?v='+n,
            controller : "train_providerCtrl"
        })
        .when('/training/title', {
            templateUrl: 'application/views/content/employee/employeeipcrlist.php?v='+n,
            controller : "train_titleCtrl"
        })
        .when('/training/venue', {
            templateUrl: 'application/views/content/employee/employeeipcrlist.php?v='+n,
            controller : "train_venueCtrl"
        })

        //Maintenance 
        .when('/mntnc/indicator', {
            templateUrl: 'application/views/content/employee/mntnc/indicator.php?v='+n,
            controller : "hris_mntnc_indicatorCtrl"
        })
        .when('/mntnc/function', {
            templateUrl: 'application/views/content/employee/mntnc/function.php?v='+n,
            controller : "hris_mntnc_functionCtrl"
        })
        // END of HRIS //


        .when('/printleave', {
            templateUrl: 'application/controllers/PrintLeave.php?v='+n,
            controller : "leaveapplicationlistCtrl"
        })
        .when('/norights', {
            templateUrl: 'application/views/content/norights.php?v='+n
        })

        //// end of FDESPI /////
        


        //// ANJON /////

        //// end of ALINGO /////
        

        .otherwise({
         templateUrl : 'application/views/content/home.php?v='+n,
         //controller : "InterviewExamHistCtrl"
        });
});


app.filter('propsFilter', function() {
  return function(items, props) {
    var out = [];

    if (angular.isArray(items)) {
      var keys = Object.keys(props);
        
      items.forEach(function(item) {
        var itemMatches = false;

        for (var i = 0; i < keys.length; i++) {
          var prop = keys[i];
          var text = props[prop].toLowerCase();
          if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
            itemMatches = true;
            break;
          }
        }

        if (itemMatches) {
          out.push(item);
        }
      });
    } else {
      // Let the output be the input untouched
      out = items;
    }

    return out;
  };
});

        
app.filter('groupBy', function(){
  return function(list, group_by) {

  var filtered = [];
  var prev_item = null;
  var group_changed = false;
  // this is a new field which is added to each item where we append "_CHANGED"
  // to indicate a field change in the list
  var new_field = group_by + '_CHANGED';

  // loop through each item in the list
  angular.forEach(list, function(item) {

    group_changed = false;

    // if not the first item
    if (prev_item !== null) {

      // check if the group by field changed
      if (prev_item[group_by] !== item[group_by]) {
        group_changed = true;
      }

    // otherwise we have the first item in the list which is new
    } else {
      group_changed = true;
    }

    // if the group changed, then add a new field to the item
    // to indicate this
    if (group_changed) {
      item[new_field] = true;
    } else {
      item[new_field] = false;
    }

    filtered.push(item);
    prev_item = item;

  });

  return filtered;
  };
})

app.filter("getDiff", function() {
  return function(time) {
    var startDate = new Date(time.startDate);
    var endDate = new Date(time.endDate);
    var milisecondsDiff = endDate - startDate;
    
      return Math.floor(milisecondsDiff/(1000*60*60)).toLocaleString(undefined, {minimumIntegerDigits: 2}) 
            + ":" + (Math.floor(milisecondsDiff/(1000*60))%60).toLocaleString(undefined, {minimumIntegerDigits: 2})  
            + ":" + (Math.floor(milisecondsDiff/1000)%60).toLocaleString(undefined, {minimumIntegerDigits: 2}) ;
  
  }
});

app.filter('startFrom', function() {
      return function(input, start) {
      if(input) {
      start = +start; //parse to int
      return input.slice(start);
      }
      return [];
      }
});


var functionService = angular.module('functionService', [])
functionService.factory('functionDataOp', ['$http','$rootScope', function ($http,$rootScope) {

    var functionDataOp = {};

    functionDataOp.getDTRdata = function(from,to){
       return $.ajax({
          type: 'post',
          url: 'get/DTRlist',
          data: {
          datefrom: from,
          dateto: to
          }
      }); 
    }

    return functionDataOp;

}]);


app.controller('PaginationController', PaginationController);
function PaginationController($scope) {
  $scope.pageChangeHandler = function(num) {
    //console.log('going to page ' + num);
  };
}
// app.controller('PaginationController', PaginationController);

//
//
// CONTROLLER functionCtrl
app.controller("functionCtrl", function ($scope, $http, $timeout,$location,$routeParams,$rootScope,$window,$route) {
$rootScope.AdminInput();

});
// end of functionCtrl CONTROLLER
//
// //


//
//
// CONTROLLER homeCtrl
app.controller("homeCtrl", function (functionDataOp,$scope, $http, $timeout,$location,$routeParams,$rootScope,$window,$route,$interval) {
  //On focus event
$rootScope.AdminInput();

var date = new Date();
$scope.theDate = date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);

$scope.theTime = new Date().toLocaleTimeString();
$interval(function () {
    $scope.theTime = new Date().toLocaleTimeString();
}, 1000);

$scope.activeTab = function (x) {
    $scope.activeTab = x;
}

$scope.getClass = function (path) {
  return ($location.path().substr(0) === path) ? 'active-sub-menu' : '';
}

shortcut.add("F1", function() {
    window.location = '#/order';
});
// shortcut.add("F2", function() {
//     // $location.path("/inventory");
//     window.location = '#/inventory';
// });
// shortcut.add("F3", function() {
//     alert("F3 pressed");
// });

var asiaTime = new Date().toLocaleString("en-US", {timeZone: "Asia/Shanghai"});
asiaTime = new Date(asiaTime);

thistime = new Date();

$scope.loadHomeData = function(){
  $rootScope.loadPreloader();

  $http.get("get/homepage").success(function(data){
    $scope.userdata = data['session'];

    // console.log($scope.userdata)
    localStorage.setItem("potstore_userdata", JSON.stringify($scope.userdata));
    $rootScope.userlogged = JSON.parse(localStorage.getItem("potstore_userdata"));

    $scope.hasAccessInventory = $rootScope.UserHasAccess('inventory');
    $scope.hasAccessMNTNC = $rootScope.UserHasAccess('mntnc');
    $scope.hasAccessREPORT = $rootScope.UserHasAccess('report');
    $scope.hasAccessUM = $rootScope.UserHasAccess('usermanage');

  }).catch(function (err) {
  }).finally(function () {
  });

  $rootScope.removePreloader();
}

$scope.loadHomeData();

});
// end of homeCtrl CONTROLLER
//
//


//
//
// CONTROLLER usermanagementCtrl
app.controller("usermanagementCtrl", function ($scope, $http, $timeout,$routeParams,$rootScope,$location) {

$rootScope.AdminInput();
$rootScope.userlogged = JSON.parse(localStorage.getItem("potstore_userdata"));

if($rootScope.userlogged['role']!='SUPERADMIN'&&!$rootScope.UserHasAccess('lms_usermanage')){
   $location.path("/norights");
   return;
}

$scope.currentPage = 1;
$scope.pageSize = 10;

$scope.loadData = function(){
  $rootScope.loadPreloader();

  $http.get("get/getUsers").success(function(data){
    $scope.tblUser = data['user'];
    $scope.tblEmployee = data['employees'];
    console.log($scope.tblUser)
  }).catch(function (err) {
    $rootScope.removePreloader();
  }).finally(function () {
    $rootScope.removePreloader();
  });
}

$scope.loadData();

$scope.loadStatList = function(slctd,item,title){
  return {
    title: title,
    filterPlaceHolder: 'Start typing to filter the lists below.',
    labelAll: 'All',
    labelSelected: 'Selected',
    helpMessage: ' Click items to transfer them between fields.',
    /* angular will use this to filter your lists */
    orderProperty: 'name',
    /* this contains the initial list of all items (i.e. the left side) */

    items: item,
    //items: [{'id': '50', 'name': 'Germany'}, {'id': '45', 'name': 'Spain'}, {'id': '66', 'name': 'Italy'}, {'id': '30', 'name' : 'Brazil' }, {'id': '41', 'name': 'France' }, {'id': '34', 'name': 'Argentina'}],
    /* this list should be initialized as empty or with any pre-selected items */
    selectedItems: slctd
  };
}

$scope.addPositionTitle = function(){
    $scope.selitems = [];
    $scope.loadQuals($scope.selitems,$scope.tblReqs);
}

$scope.editUserAccess = function(x){
  $rootScope.loadPreloader();

  $scope.e_username = x.user_name;
  $scope.e_empname = x.FirstName + ' ' + x.LastName;
  $scope.e_role = x.role;
  $scope.e_empno = x.employeeno;
  $scope.tblEmployee.selected = $scope.getSelectedEmployee(x.employeeno);

  $scope.tblJobReq = [];
  $http.get("get/getAccessDetail/" + x.user_name + "/" + x.EmployeeNo).success(function(data){
    $scope.tblModuleAccess = data['useraccess_module'];
    $scope.tblModule = data['module'];

    var itemsmodule = [];     var selitemsmodule = [];
    
    if($scope.tblModuleAccess){
      for($d=0;$d<$scope.tblModuleAccess.length;$d++){
          selitemsmodule.push($scope.tblModuleAccess[$d].Code);   
      }
    }

    for($i=0;$i<$scope.tblModule.length;$i++){
      if(selitemsmodule.indexOf($scope.tblModule[$i].Code) == -1){
        itemsmodule.push({Code:'' + $scope.tblModule[$i].Code + '',name:'' + $scope.tblModule[$i].name + ''});
      }
    }

    $scope.accessmodule = $scope.loadStatList($scope.tblModuleAccess, itemsmodule, 'Access Modules');
    $('#editUserAccess').modal({show:true});
  }).catch(function (err) {
    $rootScope.removePreloader();
  }).finally(function () {
    
  $rootScope.removePreloader();
  });

}

$scope.getSelectedEmployee = function(empid) {
  $scope.tblEmployee.selected = [];
  if($scope.tblEmployee.length>0){
    for($i=0;$i<$scope.tblEmployee.length;$i++){
      if(empid==$scope.tblEmployee[$i].EmployeeNo){
        return $scope.tblEmployee[$i];
      }
    }
  }
}

$scope.updateUserAccess = function(){
    $rootScope.loadPreloader();

    $.ajax({
          type: 'post',
          url: 'cud/saveUserAccess',
          data: {
          id: $scope.e_username,
          empno: $scope.e_empno,
          name: $scope.e_empname,
          role: $scope.e_role,
          module: $scope.accessmodule['selectedItems']
          },
          success: function (response) {
            var resp = JSON.parse(response);
            if(resp==true){
              $('#editUserAccess').modal('hide');
              $scope.loadData();
              alertify.success('User Access successfully saved !');
            }
            else{
              $('#editUserAccess').modal('hide');
              alertify.alert("Something went wrong !", 'You might not be authorized for this operation or an error occured !');
            }
          }
      }); 

    $rootScope.removePreloader();
}

$scope.getSelected = function(x){
  $scope.e_empno = x.EmployeeNo;
  $scope.e_empname = x.LastName + ', ' + x.FirstName;
}
$scope.savePositionTitle = function(){
    $rootScope.loadPreloader();

    $.ajax({
          type: 'post',
          url: 'cud/savePosTitle',
          data: {
          code: $scope.n_pt_id,
          abbrev: $scope.n_pt_abbrev,
          descr: $scope.n_pt_desc,
          grade: $scope.n_pt_grade,
          quals: $scope.qualOption['selectedItems']  
          },
          success: function (response) {
           if(response)
            {   
                swal("Saved!", "New position title added !", "success");
                $('#newPosTit').modal('hide');
                $(".modal-body input").val("");
                alertify.success('Position Title added !');
                $scope.loadData();
            }
          }
      }); 

    $rootScope.removePreloader();
}

$scope.deletePositionTitle = function(x){
    swal({
        title:"Are you sure you want to delete this position title ?",
        text: x.Description,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function () {
        $rootScope.loadPreloader();

        $.ajax({
              type: 'post',
              url: 'cud/deletePosTitle',
              data: {
              id: x.Code
              },
              success: function (response) {
               if(response)
               {
                  swal("Deleted!", "Position title deleted !", "success");
                  alertify.warning('Position Title deleted !');
                  $scope.loadData();
                }
              }
          }); 

        $rootScope.removePreloader();
        
    });
} 

}); 
// end of usermanagementCtrl CONTROLLER
//
//


//
//
// CONTROLLER inventoryCtrl
app.controller("inventoryCtrl", function (functionDataOp,$scope, $http, $timeout,$location,$routeParams,$rootScope,$window,$route,$interval) {
  //On focus event
$rootScope.AdminInput();
$rootScope.userlogged = JSON.parse(localStorage.getItem("potstore_userdata"));

if($rootScope.userlogged['role']!='SUPERADMIN'&&!$rootScope.UserHasAccess('inventory')){
   $location.path("/norights");
}


$scope.sort_by = function(predicate) {
  $scope.predicate = predicate;
  $scope.reverse = !$scope.reverse;
}

$scope.currentPage = 1;
$scope.pageSize = 10;

$scope.loadHomeData = function(){
  $scope.preloader = true;
  $http.get("get/getInventoryHome").success(function(data){
    $scope.tblInventory = data['inventory'];
    $scope.listCodeRef = data['coderef'];
  }).catch(function (err) {
  $scope.preloader = false;
  }).finally(function () {
  $scope.preloader = false;
  });
}

$scope.loadHomeData();
$scope.newprod=[{
                  ProductID : '',
                  Descr : '',
                  Descr2 : '',
                  Type : '',
                  Category : '',
                  Brand : '',
                  Model : '',
                  Unit : '',
                  ReorderQtyPoint : 0,
                  Sold : 0,
                  Available : 0,
                  Quantity : 0,
                  SellingPrice : 0,
                  LastPrice : 0,
                  LessPercentage : 0,
                  Remarks : ''}];

$scope.clearFilter = function(){ 
  $scope.newfilter=[{
                    ProductID : '',
                    Descr : '',
                    Descr2 : '',
                    Type : '',
                    Category : '',
                    Brand : '',
                    Model : '',
                    Unit : '',
                    ReorderQtyPoint : 0,
                    Sold : 0,
                    Available : 0,
                    Quantity : 0,
                    SellingPrice : 0,
                    LastPrice : 0,
                    LessPercentage : 0,
                    Remarks : ''}]; 
}

$scope.clearFilter();

$scope.saveProduct = function(x,type=0){ // 0 means insert
  if(x){
    $.ajax({
            type: 'post',
            url: 'cud/saveProduct',
            data: {
            productdata: x
            },
            success: function (response) {
              var resp = JSON.parse(response);
              if(resp==true){
                if(type==0){
                  alertify.alert("Added!", 'New product successfully added.').set('movable', true); 
                  alertify.success('New product successfully added.'); 
                  $('#newProduct').modal('hide'); 
                }
                else{
                  alertify.alert("Updated!", 'Product successfully updated.').set('movable', true); 
                  alertify.success('Product successfully updated.'); 
                  $('#editProduct').modal('hide'); 
                }
                $scope.loadHomeData(); 
              }

            }
        });

  }
  else{
    alertify.alert("No Data!", 'Kindly input data ...')
  }  
}

$scope.editProduct = function(x){
  $scope.editprod = x; 
  $('#editProduct').modal('show');
}

$scope.retrieveDTRnotexist = function(){
  $scope.preloader = true;
  $scope.record_title = 'Non-Existing daily time records from Integra';
  $rootScope.preloaderText = 'Please wait. Loading unexisting records...';

  if($scope.ret_from)
    $scope.newefrom = $rootScope.convertToDate($scope.ret_from);

  if($scope.ret_to)
    $scope.neweto = $rootScope.convertToDate($scope.ret_to);


  $scope.currentPage = 1;
  $scope.pageSize = 10;

  $.ajax({
          type: 'post',
          url: 'get/getNotExistsLog',
          data: {
          datefrom: $scope.newefrom,
          dateto: $scope.neweto
          },
          success: function (data) {
            var resp = JSON.parse(data);
            $scope.tblDTRnotExist = resp['notexist'];
            $scope.tblDTR = $scope.tblDTRnotExist;
            $scope.preloader = false;
          }
      });

}

$scope.retrieveGPDTR = function(){
  $scope.preloader = true;
  $scope.record_title = 'Integra daily time records';
  $rootScope.preloaderText = 'Please wait. Loading Integra daily time records...';

  if($scope.ret_from)
    $scope.newefrom = $rootScope.convertToDate($scope.ret_from);

  if($scope.ret_to)
    $scope.neweto = $rootScope.convertToDate($scope.ret_to);

  functionDataOp.getGPDTRdata($scope.newefrom,$scope.neweto)
    .success(function (data) {
        var ret = JSON.parse(data); 
        $scope.tblDTR = ret['dtr_gp'];
        $scope.tblDTRnotExist = [];
        $scope.currentPage = 1;
        $scope.pageSize = 10;
        $scope.preloader = false;

    })
    .error(function (error) {
        $scope.status = 'Unable to load data: ' + error.message;
        $scope.preloader = false;
    });
}

function chunkArray(myArray, chunk_size){
  var index = 0;
  var arrayLength = myArray.length;
  var tempArray = [];
  
  for (index = 0; index < arrayLength; index += chunk_size) {
      myChunk = myArray.slice(index, index+chunk_size);
      // Do something if you want with the group
      tempArray.push(myChunk);
  }

  return tempArray;
}

});
// end of inventoryCtrl CONTROLLER
//
//

//
//
// CONTROLLER orderCtrl
app.controller("orderCtrl", function (functionDataOp,$scope, $http, $timeout,$location,$routeParams,$rootScope,$window,$route,$interval) {
  //On focus event
$rootScope.AdminInput();
$rootScope.userlogged = JSON.parse(localStorage.getItem("potstore_userdata"));

var path = $location.path().split("/")[2];

if($rootScope.userlogged['role']!='SUPERADMIN'&&!$rootScope.UserHasAccess(path)){
   $location.path("/norights");
}

$scope.sort_by = function(predicate) {
  $scope.predicate = predicate;
  $scope.reverse = !$scope.reverse;
}

$scope.currentPage = 1;
$scope.pageSize = 100;
$scope.lastModal = '';
$scope.custtype = 'WalkIn';


$scope.loadHomeData = function(){
  $http.get("get/getMntncHome").success(function(data){
    $scope.tblMntnc = data['mntnc'];
  }).catch(function (err) {
  }).finally(function () {
  });
}

$scope.focusID = function(){
  $('#prodid').focus();
}

$scope.loadHomeData();
$scope.focusID();
$scope.tblOrderItem=[];
$scope.lastitem=-1;
$scope.lessDiscount = 0;
$scope.serviceFee = 0;
$scope.tenderedAmount = 0;
$scope.totalItemAmount = 0;

$scope.calculateValue = function(){
  var totalP = $scope.totalItemAmount;

  if($scope.lessDiscount!=0){
    $scope.totalAmount = (totalP - (totalP * ($scope.lessDiscount * .01))) + ($scope.serviceFee);
  }
  else{
    $scope.totalAmount = (totalP + $scope.serviceFee);
  }

  // if($scope.tenderedAmount<$scope.totalAmount){
  //   $scope.tenderedAmount = $scope.totalAmount;
  // }

  $scope.changeAmount = $scope.tenderedAmount - $scope.totalAmount;
}

$scope.calculateAvailable = function(x){
  var availqty = x.Available;
  var totalqty = 0;

  if($scope.tblOrderItem){
    for($i=0;$i<$scope.tblOrderItem.length;$i++){
      var tbl = $scope.tblOrderItem[$i];

      totalqty+=tbl.StandardSalesQty;
    }
  }

  if(availqty<totalqty){
    alertify.alert("No available stocks.");
    $scope.prodid = '';
    return false;
  }
  else{
    return true;
  }
}

$scope.updateTenderedAmount = function(){
  if($scope.tenderValue){
    $scope.tenderedAmount = $scope.tenderValue;
    $('#tender').modal('hide');
    $scope.lastModal = '';
  }
}

$interval(function () {
  $scope.calculateValue();
}, 1000);

function highlight(tableIndex) {
    // Just a simple check. If .highlight has reached the last, start again
    // if( (tableIndex+1) > $('#datatbl tbody tr').length ){
    //     tableIndex = 0;
    // }

    // Element exists?
    if($('#datatbl tbody tr:eq('+tableIndex+')').length > 0)
    {
        // Remove other highlights
        $('#datatbl tbody tr').removeClass('highlight');
        // Highlight your target
        $('#datatbl tbody tr:eq('+tableIndex+')').addClass('highlight');
    }
}

$scope.hasPopUp = function(){
  if($('.modal').hasClass('in')) {
      // alert('there is pop up'); //ID of the opened modal
      return true;
  } else {
      // alert("No pop-up opened");
      return false;
  }
}

$scope.lookUpItem = function(){
  $http.get("get/getInventoryHome").success(function(data){
    $scope.tblInventory = data['inventory'];
    $scope.listCodeRef = data['coderef'];
    $('#lookUp').modal('show');
    $scope.lastModal = 'lookUp';
  }).catch(function (err) {
  }).finally(function () {
  });
}

$scope.closelookUpItem = function(){
  $('#lookUp').modal('hide');
  $scope.lastModal = '';
}

$scope.tenderAmount = function(){
  $('#tender').modal('show');
  $('#tendered').focus();
  $scope.lastModal = 'tender';
}

$scope.closeOrderItem = function(){
  $('#editItem').modal('hide');
  $scope.lastModal = '';
}

$scope.closeTenderedAmount = function(){
  $('#tender').modal('hide');
  $scope.lastModal = '';
}


shortcut.add("up", function() {
  if($scope.lastitem>0){
    $scope.lastitem=($scope.lastitem-1);
  }
  highlight($scope.lastitem);
});

shortcut.add("down", function() {
  if($scope.tblOrderItem){
    if(($scope.lastitem>=-1)&&($scope.tblOrderItem.length-1)>$scope.lastitem){
      $scope.lastitem=($scope.lastitem+1);
    }
    highlight($scope.lastitem);
  }
});

shortcut.add("Esc", function() {
  if($scope.lastModal == 'tender'){
    $scope.closeTenderedAmount();
  }
  else if($scope.lastModal == 'editItem'){
    $scope.closeOrderItem();
  }
  else if($scope.lastModal == 'lookUp'){
    $scope.closelookUpItem();
  }
});

shortcut.add("Enter", function() {
  if($scope.lastModal == 'tender'){
    $scope.updateTenderedAmount();
  }
  else if($scope.lastModal == 'editItem'){
    $scope.updateOrderItem();
  }
  else{
    $scope.addOrderItem();
  }
});

shortcut.add("F1", function() {
  if(!$scope.hasPopUp()){
    $scope.lookUpItem();
  }
});

shortcut.add("F2", function() {
  if(!$scope.hasPopUp()){
    $scope.editOrderItem();
  }
});

shortcut.add("F3", function() {
  if(!$scope.hasPopUp()){
    $scope.removeOrderItem();
  }
});

shortcut.add("F4", function() {
  if(!$scope.hasPopUp()){
    $scope.tenderAmount();
    $('#tendered').focus();
  }
});

shortcut.add("F5", function() {
  if(!$scope.hasPopUp()){
    $scope.saveOrder();
  }
});

shortcut.add("F8", function() {
  $scope.updateOrderItem();
});

shortcut.add("F9", function() {
    $scope.focusID();
});

shortcut.add("Alt+1", function() {
  $('#tendered').focus();
});

shortcut.add("ALT+2", function() {
  $('#tb_discount').focus();
});

shortcut.add("ALT+3", function() {
  $('#tb_servfee').focus();
});

$scope.addLookUpItem = function(x){
  if(x){
      if(!$scope.calculateAvailable(x)){
        return;
      }
      $scope.tblOrderItem.push(x);

      $scope.tblOrderItem[$scope.tblOrderItem.length-1].SalePrice = x.SellingPrice;
      $scope.tblOrderItem[$scope.tblOrderItem.length-1].TotalSalePrice = $scope.getDiscountedPrice($scope.tblOrderItem[$scope.tblOrderItem.length-1].SalePrice
                                                                                                    ,$scope.tblOrderItem[$scope.tblOrderItem.length-1].StandardSalesQty
                                                                                                    ,$scope.tblOrderItem[$scope.tblOrderItem.length-1].LessPercentage);


      $scope.lastitem=($scope.tblOrderItem.length-1);
      $scope.calculatePrice();
      // $scope.prodid = '';
      $scope.closelookUpItem();
  }
}

$scope.addOrderItem = function(){
  if($scope.prodid){
    $http.get("get/getProduct/"+$scope.prodid).success(function(data){
      var prodrow = data['prod'];

      if(!$scope.calculateAvailable(prodrow[0])){
        return;
      }
      if(prodrow[0].Available<prodrow[0].StandardSalesQty){
        alertify.alert("No available stocks.");
        $scope.prodid = '';
        return;
      }

      $scope.tblOrderItem.push(prodrow[0]);

      if(prodrow[0]){
        $scope.tblOrderItem[$scope.tblOrderItem.length-1].SalePrice = prodrow[0].SellingPrice;
        $scope.tblOrderItem[$scope.tblOrderItem.length-1].TotalSalePrice = $scope.getDiscountedPrice($scope.tblOrderItem[$scope.tblOrderItem.length-1].SalePrice
                                                                                                    ,$scope.tblOrderItem[$scope.tblOrderItem.length-1].StandardSalesQty
                                                                                                    ,$scope.tblOrderItem[$scope.tblOrderItem.length-1].LessPercentage);
      }

      $scope.lastitem=($scope.tblOrderItem.length-1);
      $scope.calculatePrice();
      $scope.prodid = '';
    }).catch(function (err) {
    }).finally(function () {
    });
  }
}

$scope.editOrderItem = function(){
  if($scope.tblOrderItem){
    if($scope.lastitem>($scope.tblOrderItem.length-1)){
      $scope.lastitem=($scope.tblOrderItem.length-1);
    }

    if($scope.lastitem>-1&&$scope.tblOrderItem.length>0){
      var editprod = $scope.tblOrderItem[$scope.lastitem];
      $scope.e_ProductID = editprod.ProductID;
      $scope.e_Descr = editprod.Descr;
      $scope.e_SellingPrice = editprod.SellingPrice;
      $scope.e_LastPrice = editprod.LastPrice;
      $scope.e_Available = editprod.Available;
      $scope.e_StandardSalesQty = editprod.StandardSalesQty;
      $scope.e_SalePrice = editprod.SalePrice;
      $scope.e_LessPercentage = editprod.LessPercentage;
      $('#editItem').modal('show');
      $scope.lastModal = 'editItem';
    }
  }
}

$scope.getDiscountedPrice = function(price,qty,less=0){
  var retprice = (price * qty);
  if(less!=0){
    retprice = retprice - ((price * qty) * (less * .01));
  }
  else{
    retprice = (price * qty);
  }
  return retprice;
}

$scope.chooseItemRow = function(x,index){
    $scope.lastitem = index;
    highlight($scope.lastitem);
}

$scope.calculatePrice = function(){
  var totalP = 0;
  if($scope.tblOrderItem){
    for($i=0;$i<$scope.tblOrderItem.length;$i++){
      var tbl = $scope.tblOrderItem[$i];

      totalP+=tbl.TotalSalePrice;
    }
  }

  $scope.totalItemAmount = totalP;

  if($scope.lessDiscount!=0){
    $scope.totalAmount = (totalP - (totalP * ($scope.lessDiscount * .01))) + ($scope.serviceFee);
  }
  else{
    $scope.totalAmount = (totalP + $scope.serviceFee);
  }
  
  // $scope.tenderedAmount = $scope.totalAmount;
}

$scope.updateOrderItem = function(){
  if($scope.tblOrderItem){
    if($scope.lastitem>-1&&$scope.tblOrderItem.length>0){
      var editprod = $scope.tblOrderItem[$scope.lastitem];

      if($scope.e_Available<$scope.e_StandardSalesQty){
        alertify.alert("Not enough items!. Check the number of available items.");
        return;
      }

      if($scope.e_LastPrice>$scope.e_SalePrice){
        alertify.confirm("Are you sure you want to give price lower than the Last Price ?",
          function(){
            editprod.Descr = $scope.e_Descr;
            editprod.SellingPrice = $scope.e_SellingPrice;
            editprod.LastPrice = $scope.e_LastPrice;
            editprod.StandardSalesQty = $scope.e_StandardSalesQty;
            editprod.SalePrice = $scope.e_SalePrice;
            editprod.LessPercentage = $scope.e_LessPercentage;
            editprod.TotalSalePrice = $scope.getDiscountedPrice(editprod.SalePrice,editprod.StandardSalesQty,editprod.LessPercentage);
            $('#editItem').modal('hide');
            $scope.lastModal = '';
            return;
          },
          function(){
            return;
        });
      }
      else{
        editprod.Descr = $scope.e_Descr;
        editprod.SellingPrice = $scope.e_SellingPrice;
        editprod.LastPrice = $scope.e_LastPrice;
        editprod.StandardSalesQty = $scope.e_StandardSalesQty;
        editprod.SalePrice = $scope.e_SalePrice;
        editprod.LessPercentage = $scope.e_LessPercentage;
        editprod.TotalSalePrice = $scope.getDiscountedPrice(editprod.SalePrice,editprod.StandardSalesQty,editprod.LessPercentage);
        $('#editItem').modal('hide');
        $scope.lastModal = '';
      }
      $scope.calculatePrice();
    }
  }
  $scope.focusID();
}

$scope.removeOrderItem = function(){
  if($scope.tblOrderItem){
    if($scope.lastitem>-1&&$scope.tblOrderItem.length>0&&$scope.tblOrderItem.length>$scope.lastitem){
      alertify.confirm("Are you sure you want to remove selected item " +
                      "[Product ID : " + $scope.tblOrderItem[$scope.lastitem].ProductID + "]?",
        function(){
          $scope.tblOrderItem.splice($scope.lastitem,1);
          $scope.calculatePrice();
          return;
        },
        function(){
          return;
      });
    }
  }
}

$scope.saveOrder = function(){ 
    $rootScope.loadPreloader();
    if($scope.tenderedAmount<$scope.totalAmount||$scope.tenderedAmount<=0){
      alertify.alert("Kindly enter TENDERED AMOUNT not less than the TOTAL AMOUNT.");
      return;
    }

    $.ajax({
          type: 'post',
          url: 'cud/saveOrder',
          data: {
          id: $scope.orderID,
          totalamount: $scope.totalAmount,
          totalesslamount: 0,
          totalastlamount: 0,
          tendered: $scope.tenderedAmount,
          serviceamount: $scope.serviceFee,
          custtype: $scope.custtype,
          custid: $scope.custid,
          discount: $scope.lessDiscount,
          remarks: $scope.remarks,
          items : $scope.tblOrderItem
          },
          success: function (response) {
            var resp = JSON.parse(response);
            if(resp==true){
              swal("Saved!", "Successfully saved !", "success");
              // $scope.loadHomeData();
              alertify.success('Successfully saved !');
              $route.reload();
            }
            else{
              alertify.alert("Something went wrong !", 'Please try again later !');
            }
          }
      }); 

    $rootScope.removePreloader();
}

$scope.deleteFunction = function(x){
    swal({
        title:"Are you sure you want to delete this record ?",
        text: x.Descr,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: true
    }, function () {
        $rootScope.removePreloader();

        $.ajax({
              type: 'post',
              url: 'cud/deleteDB_CodeReference',
              data: {
              id: x.Code,
              parentcode: 'CF'
              },
              success: function (response) {
               if(response)
               {
                    // swal("Deleted!", "Success Indicator record Deleted.", "success");
                    alertify.error('[' + x.Descr + '] record Deleted.');
                    $scope.loadHomeData();
                }
              }
          }); 

        $rootScope.removePreloader();
        
    });
}

});
// end of orderCtrl CONTROLLER
//
//

//
//
// CONTROLLER mntncCtrl
app.controller("mntncCtrl", function (functionDataOp,$scope, $http, $timeout,$location,$routeParams,$rootScope,$window,$route,$interval) {
  //On focus event
$rootScope.AdminInput();
$rootScope.userlogged = JSON.parse(localStorage.getItem("potstore_userdata"));

var path = $location.path().split("/")[2];

if($rootScope.userlogged['role']!='SUPERADMIN'&&!$rootScope.UserHasAccess(path)){
   $location.path("/norights");
}

$scope.sort_by = function(predicate) {
  $scope.predicate = predicate;
  $scope.reverse = !$scope.reverse;
}

$scope.currentPage = 1;
$scope.pageSize = 10;

$scope.loadHomeData = function(){
  $http.get("get/getMntncHome").success(function(data){
    $scope.tblMntnc = data['mntnc'];
  }).catch(function (err) {
  }).finally(function () {
  });
}

$scope.loadHomeData();

$scope.editFunction = function(x){
  $scope.e_function = x;
  $('#editModalFunction').modal({show:true});
}

$scope.saveFunction = function(type,pc){ //pc = parentcode
    $rootScope.loadPreloader();
    var new_function;

    (type==0) ? new_function = $scope.n_function : new_function = $scope.e_function;

    $.ajax({
          type: 'post',
          url: 'cud/saveCodeReference',
          data: {
          id: new_function.Code,
          desc: new_function.Descr,
          parentcode: pc
          },
          success: function (response) {
            var resp = JSON.parse(response);
            if(resp==true){
              if (type==0){
                $('#newModalFunction').modal('hide');
                $scope.n_function = '';
              }
              else{
                $('#editModalFunction').modal('hide');
                $scope.e_function = '';
              }

              swal("Saved!", "Successfully saved !", "success");
              $scope.loadHomeData();
              alertify.success('Successfully saved !');
            }
            else{
              (type==0) ? $('#newModalFunction').modal('hide') : $('#editModalFunction').modal('hide');

              alertify.alert("Something went wrong !", 'Please try again later !');
            }
          }
      }); 

    $rootScope.removePreloader();
}

$scope.deleteFunction = function(x,pc){
    swal({
        title:"Are you sure you want to delete this record ?",
        text: x.Descr,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: true
    }, function () {
        $rootScope.removePreloader();

        $.ajax({
              type: 'post',
              url: 'cud/deleteDB_CodeReference',
              data: {
              id: x.Code,
              parentcode: pc
              },
              success: function (response) {
               if(response)
               {
                    // swal("Deleted!", "Success Indicator record Deleted.", "success");
                    alertify.error('[' + x.Descr + '] record Deleted.');
                    $scope.loadHomeData();
                }
              }
          }); 

        $rootScope.removePreloader();
        
    });
}

});
// end of mntncCtrl CONTROLLER
//
//

//
//
// CONTROLLER receiveCtrl
app.controller("receiveCtrl", function (functionDataOp,$scope, $http, $timeout,$location,$routeParams,$rootScope,$window,$route,$interval) {
  //On focus event
$rootScope.AdminInput();
$rootScope.userlogged = JSON.parse(localStorage.getItem("potstore_userdata"));

var path = $location.path().split("/")[2];

if($rootScope.userlogged['role']!='SUPERADMIN'&&!$rootScope.UserHasAccess(path)){
   $location.path("/norights");
}

$scope.sort_by = function(predicate) {
  $scope.predicate = predicate;
  $scope.reverse = !$scope.reverse;
}

$scope.currentPage = 1;
$scope.pageSize = 10;
$scope.lu_item = null;
$scope.curprice = 0;
$scope.newprice = 0;
$scope.aveprice = 0;

$scope.lookUpItem = function(){
  $http.get("get/getInventoryHome").success(function(data){
    $scope.tblInventory = data['inventory'];
    $scope.listCodeRef = data['coderef'];
    $('#lookUp').modal('show');
  }).catch(function (err) {
  }).finally(function () {
  });
}

$scope.lookUpItem();

$scope.hasPopUp = function(){
  if($('.modal').hasClass('in')) {
      // alert('there is pop up'); //ID of the opened modal
      return true;
  } else {
      // alert("No pop-up opened");
      return false;
  }
}

shortcut.add("F1", function() {
  if(!$scope.hasPopUp()){
    $scope.lookUpItem();
  }
});

$scope.closeLookUp = function(){
  if($scope.lu_item) {
    $('#lookUp').modal('hide');
  } else {
    alertify.alert("Kindly choose an item to receive.");
  }
}

$scope.calculateAvePrice = function(){
  var ave_price = 0;
  ave_price = (($scope.curprice * $scope.lu_item.Available) + ($scope.newprice * $scope.qty)) 
                      / ($scope.lu_item.Available + $scope.qty);
                      
  var iNum = parseFloat(ave_price);
  if(!isNaN(iNum)){
    $scope.aveprice = iNum.toFixed(2);
  }
}

$scope.selectLookUpItem = function(x){
  if(x){
    $scope.lu_item = x;
    $scope.curprice = x.SellingPrice;
    $scope.curqty = x.Available;
    $('#lookUp').modal('hide');
  }
}

$scope.saveProductReceive = function(){ 
    $rootScope.loadPreloader();

    $.ajax({
          type: 'post',
          url: 'cud/saveReceivedProduct',
          data: {
          id: null,
          prodid: $scope.lu_item.ProductID,
          curprice: $scope.curprice,
          orderprice: $scope.orderprice,
          newprice: $scope.newprice,
          aveprice: $scope.aveprice,
          avelastprice: $scope.avelastprice,
          qty: $scope.qty,
          supplier: $scope.suppid,
          delby: $scope.deliveredby,
          deldate: $rootScope.convertToDate($scope.deliverydate),
          remarks : $scope.remarks
          },
          success: function (response) {
            var resp = JSON.parse(response);
            if(resp==true){
              swal("Saved!", "Successfully saved !", "success");
              // $scope.loadHomeData();
              alertify.success('Successfully saved !');
              $route.reload();
            }
            else{
              alertify.alert("Something went wrong !", 'Please try again later !');
            }
          }
      }); 

    $rootScope.removePreloader();
}

$scope.saveFunction = function(type,pc){ //pc = parentcode
    $rootScope.loadPreloader();
    var new_function;

    (type==0) ? new_function = $scope.n_function : new_function = $scope.e_function;

    $.ajax({
          type: 'post',
          url: 'cud/saveCodeReference',
          data: {
          id: new_function.Code,
          desc: new_function.Descr,
          parentcode: pc
          },
          success: function (response) {
            var resp = JSON.parse(response);
            if(resp==true){
              if (type==0){
                $('#newModalFunction').modal('hide');
                $scope.n_function = '';
              }
              else{
                $('#editModalFunction').modal('hide');
                $scope.e_function = '';
              }

              swal("Saved!", "Successfully saved !", "success");
              $scope.loadHomeData();
              alertify.success('Successfully saved !');
            }
            else{
              (type==0) ? $('#newModalFunction').modal('hide') : $('#editModalFunction').modal('hide');

              alertify.alert("Something went wrong !", 'Please try again later !');
            }
          }
      }); 

    $rootScope.removePreloader();
}

$scope.deleteFunction = function(x,pc){
    swal({
        title:"Are you sure you want to delete this record ?",
        text: x.Descr,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: true
    }, function () {
        $rootScope.removePreloader();

        $.ajax({
              type: 'post',
              url: 'cud/deleteDB_CodeReference',
              data: {
              id: x.Code,
              parentcode: pc
              },
              success: function (response) {
               if(response)
               {
                    // swal("Deleted!", "Success Indicator record Deleted.", "success");
                    alertify.error('[' + x.Descr + '] record Deleted.');
                    $scope.loadHomeData();
                }
              }
          }); 

        $rootScope.removePreloader();
        
    });
}

});
// end of receiveCtrl CONTROLLER
//
//

//
//
// CONTROLLER rep_salesCtrl
app.controller("rep_salesCtrl", function (functionDataOp,$scope, $http, $timeout,$location,$routeParams,$rootScope,$window,$route,$interval) {
  //On focus event
$rootScope.AdminInput();
$rootScope.userlogged = JSON.parse(localStorage.getItem("potstore_userdata"));

var path = $location.path().split("/")[2];

if($rootScope.userlogged['role']!='SUPERADMIN'&&!$rootScope.UserHasAccess(path)){
   $location.path("/norights");
}

$scope.sort_by = function(predicate) {
  $scope.predicate = predicate;
  $scope.reverse = !$scope.reverse;
}

$scope.currentPage = 1;
$scope.pageSize = 10;
$scope.s_from = $rootScope.newDate();
$scope.s_to = $rootScope.newDate();

$scope.loadReportSales = function(){
  $scope.preloader = true;
  var new_s_from, new_s_to;

  if(!$scope.s_from)
    new_s_from = $rootScope.newDate();
  else
    new_s_from = $rootScope.convertToDate($scope.s_from);

  if(!$scope.s_to)
    new_s_to = $rootScope.newDate();
  else
    new_s_to = $rootScope.convertToDate($scope.s_to);


  $.ajax({
        type: 'post',
        url: 'get/getReport_Sales',
        data: {
        from: new_s_from,
        to: new_s_to
        },
        success: function (response) {
          var resp = JSON.parse(response);
          if(resp){
            $scope.tblReportSales = resp['sales'];
            $scope.calculateTotal();
            $scope.preloader = false;
          }
          else{
            alertify.alert("No Data!", 'Please try again later !');
            $scope.preloader = false;
          }
        }
    }); 
}

// $scope.loadHomeData();

$scope.calculateTotal = function(){
  var new_tsold = 0;
  var new_tsoldamount = 0;
  var new_treturn = 0;
  var new_treturnamount = 0;
  var new_tsales = 0;
  var new_tprofit = 0;

  if($scope.tblReportSales){
    for($i=0;$i<$scope.tblReportSales.length;$i++){
      var tbl = $scope.tblReportSales[$i];

      new_tsold+=tbl.saleqty;
      new_tsoldamount+=tbl.saleamount;
      new_treturn+=tbl.returnqty;
      new_treturnamount+=tbl.returnamount;
      new_tsales+=tbl.totalsales;
      new_tprofit+=tbl.totalprofit;
    }
  }

  $scope.TotalSold=new_tsold;
  $scope.TotalSoldAmount=new_tsoldamount;
  $scope.TotalReturned=new_treturn;
  $scope.TotalReturnedAmount=new_treturnamount;
  $scope.TotalSales=new_tsales;
  $scope.TotalProfit=new_tprofit;
}

$scope.editFunction = function(x){
  $scope.e_function = x;
  $('#editModalFunction').modal({show:true});
}

$scope.saveFunction = function(type,pc){ //pc = parentcode
    $rootScope.loadPreloader();
    var new_function;

    (type==0) ? new_function = $scope.n_function : new_function = $scope.e_function;

    $.ajax({
          type: 'post',
          url: 'cud/saveCodeReference',
          data: {
          id: new_function.Code,
          desc: new_function.Descr,
          parentcode: pc
          },
          success: function (response) {
            var resp = JSON.parse(response);
            if(resp==true){
              if (type==0){
                $('#newModalFunction').modal('hide');
                $scope.n_function = '';
              }
              else{
                $('#editModalFunction').modal('hide');
                $scope.e_function = '';
              }

              swal("Saved!", "Successfully saved !", "success");
              $scope.loadHomeData();
              alertify.success('Successfully saved !');
            }
            else{
              (type==0) ? $('#newModalFunction').modal('hide') : $('#editModalFunction').modal('hide');

              alertify.alert("Something went wrong !", 'Please try again later !');
            }
          }
      }); 

    $rootScope.removePreloader();
}

});
// end of rep_salesCtrl CONTROLLER
//
//
