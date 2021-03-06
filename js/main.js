angular
    .module('burgerApp', ['ui.bootstrap', 'ui.router', 'phoneVal'])

    // routes on website
    .config(function($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise("/");

        $stateProvider
            .state('root', {
                url: "/"
            })
            .state('error', {
                url: "/error",
                templateUrl: '../templates/error.tpl.html'
            })
            .state('success', {
                url: "/success",
                templateUrl: '../templates/success.tpl.html'
            })
            .state('pending', {
                url: "/pending",
                templateUrl: '../templates/pending.tpl.html'
            })
            .state('failure', {
                url: "/failure",
                templateUrl: '../templates/failure.tpl.html'
            })
            .state('closed', {
                url: "/closed",
                templateUrl: '../templates/closed.tpl.html'
            });
        })

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
	.controller('MainController', function($scope, $document, $filter, $location, $http, $rootScope, $state, $window, $timeout) {

        $rootScope.$state = $state;
        $scope.minDeliveryPrice = 300;
        $scope.deliveryPrice = 50;

        initialize();
        function initialize() {
            var current = new Date().getHours();
            // redirect to a /closed state if site accessed at non-working hours
            if (current < 10 || current >= 23) {
                setRoute('closed');
                return false;
            }
            return true;
        }
        function setRoute(state) {
            $timeout(function() {
                $state.go(state);
            });
        }

        $scope.orderBtnDisabled = false;

        $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
            initialize();
            if (toState.name === 'closed' && initialize()) {
                setRoute('root');
            }
        });

        $rootScope.$on('$stateChangeSuccess', function() {
            // scroll to top when state event fired
            $window.scrollTo(0, 0);
            $scope.orderBtnDisabled = false;
        });

        // data

        $scope.menu = {
            burgers: [
                {
                    name: "Класичний",
                    desc: "Булка з сезамом, котлета з яловичини приготована на вугіллі з листям салату, соленим огірком, цибулею, помідором і соусом",
                    img: "img/burger1.png",
                    price: 85,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "Класичний з сиром",
                    desc: "Класичний бургер з американським сиром чеддер",
                    img: "img/burger2.png",
                    price: 90,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "Грибний з сиром",
                    desc: "Булка з сезамом, котлета з яловичини приготована на вугіллі рясно полита грибним соусом з листям салату, печерицями на грилі та плавленим швейцарським сиром",
                    img: "img/burger3.png",
                    price: 90,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "Мексиканський",
                    desc: "Булка з сезамом, котлета з яловичини приготована на вугіллі з цибулею на грилі, гострим перцем, листям салату, подвійним американським сиром чеддер і соусом",
                    img: "img/burger4.png",
                    price: 95,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "Барбек’ю-бейкон",
                    desc: "Булка з сезамом, котлета з яловичини приготована на вугіллі з класичним барбек’ю соусом, цибулею на грилі, листям салату, хрумким бейконом, сиром чеддер і домашнім майонезом",
                    img: "img/burger5.png",
                    price: 95,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "Сулугунi",
                    desc: "Булка з сезамом, котлета з яловичини приготована на вугіллі з сиром сулугуні в середині, листям салату, помідором, соленими огірками, цибулею та соусом",
                    img: "img/burger6.png",
                    price: 90,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "Дитячий",
                    desc: "Булочка, міні котлета з яловичини приготована на вугіллі з листям салату, помідором, соленими огірками, цибулею та соусом",
                    img: "img/burger10.png",
                    price: 70,
                    qty: 0,
                    doneness: [],
                    type: "burgers"
                },
                {
                    name: "З куркою",
                    details: "",
                    desc: "Булка з сезамом, куряче філе приготоване на вугіллі з соусом айолі, листям салату, помідором та соленими огірками",
                    img: "img/burger7.png",
                    price: 75,
                    qty: 0,
                    type: "burgers2"
                },
                {
                    name: "З куркою",
                    details: "в медово-гiрчичному соусi",
                    desc: "Булка з сезамом, куряче філе приготоване на вугіллі з швейцарським сиром, медово-гірчичним соусом, соленими огірками та домашнім майонезом",
                    img: "img/burger8.png",
                    price: 80,
                    qty: 0,
                    type: "burgers2"
                },
                {
                    name: "Вегабургер",
                    details: "",
                    desc: "Булка з сезамом, котлета з нуту приготована на вугіллі з листям салату, помідором, соленими огірками, цибулею та домашнім майонезом",
                    img: "img/burger9.png",
                    price: 75,
                    qty: 0,
                    type: "burgers2"
                },
                {
                    name: "Грiшник",
                    details: "",
                    desc: "Булка з сезамом, подвійна соковита котлета з яловичини приготована на вугіллі з хрумким бейконом, сиром, соленим огірком, цибулею, гострим перцем та соусом барбек'ю",
                    img: "img/burger11.png",
                    price: 180,
                    qty: 0,
                    doneness: [],
                    type: "burgers2"
                },
                {
                    name: "З лососем на грилi",
                    details: "",
                    desc: "Булка з сезамом, соковитий стейк з лосося приготований на вугіллі з листям айсберг, огірком, цибулею та домашнім соусом тартар",
                    img: "img/burger12.png",
                    price: 150,
                    qty: 0,
                    type: "burgers2"
                },
                {
                    name: "Блю-чiз",
                    details: "з шпинатом та грибами",
                    desc: "Булка з сезамом, соковита котлета з яловичини приготована на вугіллі з листям шпинату, грибами, помідором та соусом блю-чіз",
                    img: "img/burger13.png",
                    price: 95,
                    qty: 0,
                    doneness: [],
                    type: "burgers2"
                },
                {
                    name: "Ранч",
                    details: "",
                    desc: "Булка з сезамом, соковита котлета з яловичини приготована на вугіллі, рукола, сир чеддер, смажені гриби, цибуля фрі та соус ранч",
                    img: "img/burger14.JPG",
                    price: 110,
                    qty: 0,
                    doneness: [],
                    type: "burgers2"
                }
            ],
            salads: [
                { name: "З куркою на грилi", price: 65, qty: 0, checked: false, type: "salads"},
                { name: "З яловичиною", price: 75, qty: 0, checked: false, type: "salads"},
                { name: "Вегетарiанський", price: 60, qty: 0, checked: false, type: "salads"}
            ],
            fries: [
                { name: "Натуральна", price: 30, qty: 0, checked: false, type: "fries"},
                { name: "Сирна", price: 35, qty: 0, checked: false, type: "fries"},
                { name: "Часникова", price: 35, qty: 0, checked: false, type: "fries"},
                { name: "Гостра", price: 35, qty: 0, checked: false, type: "fries"}
            ],
            drinks: [
                { name: "Pepsi", details: "0.5", price: 25, qty: 0, checked: false, type: "drinks"},
                { name: "Pepsi Light", details: "0.5", price: 25, qty: 0, checked: false, type: "drinks"},
                { name: "Dr. Pepper", details: "0.35", price: 50, qty: 0, checked: false, type: "drinks"},
                { name: "Schweppes", details: "0.5", price: 25, qty: 0, checked: false, type: "drinks"},
                { name: "7UP", details: "0.5", price: 25, qty: 0, checked: false, type: "drinks"},
                { name: "Mirinda", details: "0.5", price: 25, qty: 0, checked: false, type: "drinks"},
                { name: "Redbull", details: "0.25", price: 45, qty: 0, checked: false, type: "drinks"}
            ],
            water: [
                {name: "Аква Мiнерале газ.", details: "0.5", price: 20, qty: 0, checked: false, type: "water"},
                {name: "Аква Мiнерале негаз.", details: "0.5", price: 20, qty: 0, checked: false, type: "water"},
                {name: "Поляна Квасова газ.", details: "0.5", price: 35, qty: 0, checked: false, type: "water"}
            ],
            sauces: [
                {name: "Барбек’ю", price: 20, qty: 0, checked: false, type: "sauces"},
                {name: "Чiлi", price: 20, qty: 0, checked: false, type: "sauces"},
                {name: "Часниковий", price: 20, qty: 0, checked: false, type: "sauces"},
                {name: "Грибний", price: 20, qty: 0, checked: false, type: "sauces"}
            ]
        };
        // end data

        $scope.orig = angular.copy($scope.menu);

        $scope.reset = function() {
            $scope.menu = angular.copy($scope.orig);
        };

        // if(localStorage['menu']) {
        //     $.extend(true, $scope.menu, JSON.parse(localStorage['menu']));
        //     $scope.fullOrderDetails = {
        //         ordered: [],
        //         userName: localStorage.userName,
        //         phoneNumber: localStorage.phoneNumber,
        //         textMessage: localStorage.textMessage,
        //         paymentMethod: localStorage.paymentMethod,
        //         totalPrice: 0,
        //         rememberOrder: true
        //     };
        // } else {
        $scope.fullOrderDetails = {
            ordered: [],
            userName: "",
            phoneNumber: "",
            textMessage: "",
            paymentMethod: "cashPayment",
            totalPrice: 0,
            rememberOrder: false
        };
        // }

        $scope.addItemWithCheckbox = function(item) {
            if (item.checked) {
                item.qty = 1;
            } else {
                item.qty = 0;
            }
        }
        // for burgers
        $scope.itemIncrement = function(item) {
            item.qty += 1;
            if (item.hasOwnProperty('doneness')) {
                item.doneness.push("medium");
            }
        }
        $scope.itemDecrement = function(item) {
            item.qty -= 1;
            if (item.hasOwnProperty('doneness')) {
                item.doneness.splice(-1,1);
            } else if(item.hasOwnProperty('checked')) {
                if (item.qty == 0) {
                    item.checked = false;
                }
            }
        }
        $scope.qtyIncrement = function(item) {
            item.qty += 1;
            if (item.hasOwnProperty('doneness')) {
                item.doneness.push("medium");
            }
        }
        $scope.qtyDecrement = function(item) {
            if (item.hasOwnProperty('doneness')) {
                item.doneness.splice(-1,1);
            }
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
            var total  = {
                sum: 0,
                includeDelivery: null
            };
            for (var array in $scope.menu) {
                total.sum += $scope.menu[array].reduce(function(acc, el) {
                    return acc + el.price * el.qty
                }, 0);
            }
            // adding delivery price if sum is bigger than 100 UAH
            if (total.sum < 300) {
                total.sum += 50;
                total.includeDelivery = true;
            }
            return total;
        }

        $scope.getFullOrderDetails = function(form) {

            if (form.$invalid) {
                return;
            }

            $scope.orderBtnDisabled = true;

            $scope.fullOrderDetails.ordered = [];


            for (var array in $scope.menu) {
                for (var i=0; i < $scope.menu[array].length; i++) {
                    if ($scope.menu[array][i].qty > 0) {
                        $scope.fullOrderDetails.ordered.push($scope.menu[array][i]);
                    }
                }
            }

            $scope.fullOrderDetails.totalPrice = $scope.calcTotal();
            $scope.data = $scope.fullOrderDetails;
            // if ($scope.fullOrderDetails.rememberOrder) {
            //     localStorage['menu'] = JSON.stringify($scope.menu);
            //     localStorage['userName'] = $scope.fullOrderDetails.userName;
            //     localStorage['phoneNumber'] = $scope.fullOrderDetails.phoneNumber;
            //     localStorage['textMessage'] = $scope.fullOrderDetails.textMessage;
            //     localStorage['paymentMethod'] = $scope.fullOrderDetails.paymentMethod;
            // } else {
            //     localStorage.clear();
            //     localStorage['menu'] = '';
            // }

            //function getURLParameter(name) {
            //    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
            //}
            //
            //var d = getURLParameter('XDEBUG_SESSION_START');
            //
            //
            //$http.post('../scripts/pay.php' + '?XDEBUG_SESSION_START=' + d, $scope.data).success(function ($data) {
            //    debugger
            //});


            $http.post('../scripts/pay.php', $scope.data).success(function ($data) {
                location.replace($data);
                location.href = $data;

                if (!$scope.fullOrderDetails.rememberOrder) {
                    $scope.fullOrderDetails = {};
                    $scope.fullOrderDetails.paymentMethod = 'cashPayment';
                    $scope.reset();

                    form.$setPristine();
                    form.$setUntouched();
                }
            });

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
});
