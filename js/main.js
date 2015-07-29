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
	.controller('MainController', function($scope, $document, $filter) {

        // data
        
        $scope.menu = {
            burgers: [
                {
                    name: "Класичний",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger1.png",
                    price: 86,
                    qty: 0,
                    doneness: []
                },
                {
                    name: "Класичний з сиром",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger2.png",
                    price: 86,
                    qty: 0,
                    doneness: []
                },
                {
                    name: "З грибами та сиром",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger3.png",
                    price: 86,
                    qty: 0,
                    doneness: []
                },
                {
                    name: "Мексиканський",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger4.png",
                    price: 86,
                    qty: 0,
                    doneness: []
                },
                {
                    name: "Барбекью-бейкон",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger5.png",
                    price: 86,
                    qty: 0,
                    doneness: []
                },
                {
                    name: "Сулугунi",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger6.png",
                    price: 86,
                    qty: 0,
                    doneness: []
                },
                {
                    name: "З куркою",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger7.png",
                    price: 86,
                    qty: 0,
                    doneness: []
                },
                {
                    name: "З куркою",
                    details: "в медово-гiрчичному соусi",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger8.png",
                    price: 86,
                    qty: 0,
                    doneness: []
                },
                {
                    name: "Вегабургер",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger9.png",
                    price: 86,
                    qty: 0,
                    doneness: []
                },
                {
                    name: "Дитячий",
                    desc: "Булка з сезамом та котлета з яловичини з листям салату, соленим огiрком, цибулею, помiдором i соусом",
                    img: "img/burger10.png",
                    price: 86,
                    qty: 0,
                    doneness: []
                }
            ],
            salads: [
                { name: "З куркою-гриль", price: 33, qty: 0, checked: false},
                { name: "З яловичиною", price: 20, qty: 0, checked: false},
                { name: "Вегетарiанський", price: 25, qty: 0, checked: false}
            ],
            fries: [
                { name: "Натуральна", price: 33, qty: 0, checked: false},
                { name: "Сирна", price: 20, qty: 0, checked: false},
                { name: "Часникова", price: 25, qty: 0, checked: false},
                { name: "Гостра", price: 25, qty: 0, checked: false}
            ],
            drinks: [
                { name: "Pepsi", details: "0.5", price: 20, qty: 0, checked: false},
                { name: "Pepsi Light", details: "0.5", price: 20, qty: 0, checked: false},
                { name: "Schweppes", details: "0.5", price: 20, qty: 0, checked: false},
                { name: "7UP", details: "0.5", price: 20, qty: 0, checked: false},
                { name: "Mirinda", details: "0.5", price: 20, qty: 0, checked: false},
                { name: "Redbull", details: "0.5", price: 20, qty: 0, checked: false}
            ],
            beer: [
                {name: "Corona", details: "0.33", price: 33, qty: 0, checked: false},
                {name: "Bavaria", details: "0.5", price: 20, qty: 0, checked: false},
                {name: "STELLA ARTOIS N/A", details: "0.5", price: 25, qty: 0, checked: false}
            ],
            water: [
                {name: "AQ. MINERALE AERATED", details: "0.6", price: 20, qty: 0, checked: false},
                {name: "AQ. MINERALE STILL", details: "0.6", price: 20, qty: 0, checked: false},
                {name: "BORJOMI AERATED", details: "0.33", price: 20, qty: 0, checked: false}
            ]
        }
        // end data

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

        $scope.calcTotal = function() {
            var sum  = 0;
            for (var array in $scope.menu) {
                sum += $scope.menu[array].reduce(function(acc, el) { 
                    return acc + el.price * el.qty 
                }, 0);
            }
            return sum;
        }

        $scope.fullOrderDetails = {
            ordered: [],
            phoneNumber: "",
            textMessage: "",
            paymentMethod: "cashPayment"
        };

        // for (var array in $scope.menu) {
        //     for (var i=0; i < $scope.menu[array].length; i++) {
        //         if ($scope.menu[array][i].qty > 0) {
        //             $scope.fullOrderDetails.ordered.push($scope.menu[array][i]);
        //         }
        //     }
        // }

        $scope.getFullOrderDetails = function() {
            var data;
            for (var array in $scope.menu) {
                for (var i=0; i < $scope.menu[array].length; i++) {
                    if ($scope.menu[array][i].qty > 0) {
                        $scope.fullOrderDetails.ordered.push($scope.menu[array][i]);
                    }
                }
            }
            // return $scope.fullOrderDetails;
            data = JSON.stringify($scope.fullOrderDetails);
            console.log(data);
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

