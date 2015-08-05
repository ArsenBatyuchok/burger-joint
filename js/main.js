angular
    .module('burgerApp', ['ui.bootstrap'])

    // filter for orders
    .filter('ordered', function () {
        return function (items) {
            var ordered = [];

            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                if (item.qty > 0) {
                    ordered.push(item);
                }
            }
            return ordered;
        };
    })

    // Controller
	.controller('MainController', function($scope, $document, $filter, $location) {

        // data

        $scope.menu = {
            burgers: [
                {
                    name: "Класичний",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger1.png",
                    price: 86,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "Класичний з сиром",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger2.png",
                    price: 86,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "З грибами та сиром",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger3.png",
                    price: 86,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "Мексиканський",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger4.png",
                    price: 86,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "Барбекью-бейкон",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger5.png",
                    price: 86,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "Сулугунi",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger6.png",
                    price: 86,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "З куркою",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger7.png",
                    price: 86,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "З куркою",
                    details: "в медово-гiрчичному соусi",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger8.png",
                    price: 86,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "Вегабургер",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger9.png",
                    price: 86,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "Дитячий",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger10.png",
                    price: 86,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                }
            ],
            salads: [
                { name: "З куркою-гриль", price: 33, qty: 0, checked: false, type: "salads"},
                { name: "З яловичиною", price: 20, qty: 0, checked: false, type: "salads"},
                { name: "Вегетарiанський", price: 25, qty: 0, checked: false, type: "salads"}
            ],
            fries: [
                { name: "Натуральна", price: 33, qty: 0, checked: false, type: "fries"},
                { name: "Сирна", price: 20, qty: 0, checked: false, type: "fries"},
                { name: "Часникова", price: 25, qty: 0, checked: false, type: "fries"},
                { name: "Гостра", price: 25, qty: 0, checked: false, type: "fries"}
            ],
            drinks: [
                { name: "Pepsi", details: "0.5", price: 20, qty: 0, checked: false, type: "drinks"},
                { name: "Pepsi Light", details: "0.5", price: 20, qty: 0, checked: false, type: "drinks"},
                { name: "Schweppes", details: "0.5", price: 20, qty: 0, checked: false, type: "drinks"},
                { name: "7UP", details: "0.5", price: 20, qty: 0, checked: false, type: "drinks"},
                { name: "Mirinda", details: "0.5", price: 20, qty: 0, checked: false, type: "drinks"},
                { name: "Redbull", details: "0.5", price: 20, qty: 0, checked: false, type: "drinks"}
            ],
            beer: [
                {name: "Corona", details: "0.33", price: 33, qty: 0, checked: false, type: "beer"},
                {name: "Bavaria", details: "0.5", price: 20, qty: 0, checked: false, type: "beer"},
                {name: "STELLA ARTOIS N/A", details: "0.5", price: 25, qty: 0, checked: false, type: "beer"}
            ],
            water: [
                {name: "AQ. MINERALE AERATED", details: "0.6", price: 20, qty: 0, checked: false, type: "water"},
                {name: "AQ. MINERALE STILL", details: "0.6", price: 20, qty: 0, checked: false, type: "water"},
                {name: "BORJOMI AERATED", details: "0.33", price: 20, qty: 0, checked: false, type: "water"}
            ]
        }
        // end data

        if(localStorage['menu']) {
            $.extend(true, $scope.menu, JSON.parse(localStorage['menu']));
            $scope.fullOrderDetails = {
                ordered: [],
                phoneNumber: localStorage['phoneNumber'],
                textMessage: localStorage['textMessage'],
                paymentMethod: localStorage['paymentMethod'],
                totalPrice: 0,
                rememberOrder: true
            };
        } else {
            $scope.fullOrderDetails = {
                ordered: [],
                phoneNumber: "",
                textMessage: "",
                paymentMethod: "cashPayment",
                totalPrice: 0,
                rememberOrder: false
            };
        }
        $scope.addItemWithCheckbox = function(item) {
            if (item.checked) {
                item.qty = 1;
            } else {
                item.qty = 0;
            }
        }
        $scope.burgerIncrement = function(item) {
            item.qty = item.qty + 1;
            item.doneness.push("medium");
        }
        $scope.burgerDecrement = function(item) {
            item.qty = item.qty - 1;
            item.doneness.splice(-1,1);
        }
        $scope.qtyIncrement = function(item) {
            if(item.hasOwnProperty('checked')) {
                item.qty += 1;
            } else {
                item.qty += 1;
            }
        }
        $scope.qtyDecrement = function(item) {
            if(item.hasOwnProperty('checked')) {
                item.qty -= 1;
                if (item.qty == 0) {
                    item.checked = false;
                }
            } else {
                item.qty -= 1;
            }
        }

        $scope.calcTotal = function() {
            var sum  = 0;
            for (var array in $scope.menu) {
                //console.log($scope.menu[array]);
                sum += $scope.menu[array].reduce(function(acc, el) { 
                    return acc + el.price * el.qty 
                }, 0);
            }
            return sum;
        }

        // for (var array in $scope.menu) {
        //     for (var i=0; i < $scope.menu[array].length; i++) {
        //         if ($scope.menu[array][i].qty > 0) {
        //             $scope.fullOrderDetails.ordered.push($scope.menu[array][i]);
        //         }
        //     }
        // }

        $scope.getFullOrderDetails = function() {
            for (var array in $scope.menu) {
                for (var i=0; i < $scope.menu[array].length; i++) {
                    if ($scope.menu[array][i].qty > 0) {
                        $scope.fullOrderDetails.ordered.push($scope.menu[array][i]);
                    }
                }
            }
            $scope.fullOrderDetails.totalPrice = $scope.calcTotal();
            $scope.data = JSON.stringify($scope.fullOrderDetails);
            if ($scope.fullOrderDetails.rememberOrder) {
                localStorage['menu'] = JSON.stringify($scope.menu);
                localStorage['phoneNumber'] = $scope.fullOrderDetails.phoneNumber;
                localStorage['textMessage'] = $scope.fullOrderDetails.textMessage;
                localStorage['paymentMethod'] = $scope.fullOrderDetails.paymentMethod;
            } else {
                localStorage['menu'] = '';
            }
            location.href = '../scripts/pay.php?data='+$scope.data;
            //return $scope.data;
        }

        $scope.showFailureMessage = false;
        $scope.showSuccessMessage = false;
        $scope.currentPath = $location.path();

        if($scope.currentPath == 'failure') {
            $scope.showFailureMessage = true;
        } else if ($scope.currentPath == 'success') {
            $scope.showSuccessMessage = true;
        } else if ($scope.currentPath == 'pending') {
            $scope.showPendingMessage = true;
        } else if ($scope.currentPath == 'error') {
            $scope.showErrorMessage = true;
        }
    })

// doneness directive
.directive('offClick', function($document, $parse, $timeout) {
  return {
    restrict: 'A',
    compile: function(tElement, tAttrs) {
      var fn = $parse(tAttrs.offClick);
      
      return function(scope, iElement, iAttrs) {
        function eventHandler(ev) {
          if (iElement[0].contains(ev.target)) {
            $document.one('click touchend', eventHandler);
          } else {
            scope.$apply(function() {
              fn(scope);
            });
          }
        }
        scope.$watch(iAttrs.offClickActivator, function(activate) {
          if (activate) {
            $timeout(function() {
                    $document.one('click touchend', eventHandler);
            });
          } else {
            $document.off('click touchend', eventHandler);
          }
        });
      };
    }
  }
}
);

