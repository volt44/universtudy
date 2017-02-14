var app = angular.module('unibook', ['ngRoute']);

app.service('PanelService', [function(){
    this.panel = '';
    
    this.isActive = function(panel){
        return this.panel == panel;
    };
 
    this.setActive = function(clickedPanel){
        this.panel = clickedPanel;
    };
}]);

app.service('TokenService', ['$rootScope', function($rootScope){
    this.token = '';
    this.tokenType = '';
    this.userId = 0;
    
    this.setToken = function(token, tokenType, userId){
        this.token = token;
        this.tokenType = tokenType;
        this.userId = userId;
        $rootScope.$broadcast('tokenSet');
    };
    
    this.getToken = function(){
        return this.token;
    };
    
    this.isTokenType = function(tokenType) {
        
        return this.tokenType == tokenType;
    };
    
    this.getTokenType = function() {
        
        return this.tokenType;
    };
    
    this.getConfig = function(){
        return {headers: { 'X-Bearer-Token': this.token} };
    };
    
    this.getUserId = function(){
        return this.userId;
    }
}]);

app.service('EmployeesService', ['$http', 'TokenService', function($http, TokenService){
    this.getEmployees = function(){
        return $http.get("http://localhost/bookstore/api.php/employees", TokenService.getConfig());
    }; 
}]);

app.service('StudentsService', ['$http', 'TokenService', function($http, TokenService){
    this.getStudents = function(){
        return $http.get("http://localhost/bookstore/api.php/students", TokenService.getConfig());
    }; 
}]);

app.service('BooksService', ['$http', 'TokenService', function($http, TokenService){
    this.getBooks = function(){
        return $http.get("http://localhost/bookstore/api.php/books", TokenService.getConfig());
    }; 
}]);

app.service('JournalsService', ['$http', 'TokenService', function($http, TokenService){
    this.getJournals = function(){
        return $http.get("http://localhost/bookstore/api.php/journals", TokenService.getConfig());
    }; 
}]);

app.service('PapersService', ['$http', 'TokenService', function($http, TokenService){
    this.getPapers = function(){
        return $http.get("http://localhost/bookstore/api.php/papers", TokenService.getConfig());
    }; 
}]);

app.service('ShopService', ['$http', '$rootScope', 'TokenService', function($http, $rootScope, TokenService){    
    this.getCarts = function(){
        return $http.get("http://localhost/bookstore/api.php/carts", TokenService.getConfig());
    };
    
    this.buyBook = function(book) {
        var data = {};
        data.item_id = book.id;
        data.item_type = "book";
        data.item_cost = book.cost_buy;
        data.cost_type = "buy";
        data.quantity = 1;
        data.total = (book.cost_buy * data.quantity);
        data.user_type = "student";
        data.user_id = TokenService.getUserId();
        
        $http.post("http://localhost/bookstore/api.php/carts", data, TokenService.getConfig());
        
        $rootScope.$broadcast('cartUpdated');
    }
    
    this.rentBook = function(book){
        var data = {};
        
        data.item_id = book.id;
        data.item_type = "Book";
        data.item_cost = book.cost_borrow;
        data.cost_type = "Rent";
        data.quantity = 1;
        data.total = (book.cost_borrow * data.quantity);
        data.user_type = "student";
        data.user_id = TokenService.getUserId();
        
        $http.post("http://localhost/bookstore/api.php/carts", data, TokenService.getConfig());
        
        $rootScope.$broadcast('cartUpdated');
    }
    
    this.buyJournal = function(journal){
        var data = {};
        
        data.item_id = journal.id;
        data.item_type = "Journal";
        data.item_cost = journal.cost_doc;
        data.cost_type = "buy";
        data.quantity = 1;
        data.total = (journal.cost_doc * data.quantity);
        data.user_type = "student";
        data.user_id = TokenService.getUserId();
        
        $http.post("http://localhost/bookstore/api.php/carts", data, TokenService.getConfig());
        
        $rootScope.$broadcast('cartUpdated');
    }
    
    this.buyPaper = function(paper){
        var data = {};
        
        data.item_id = paper.id;
        data.item_type = "Paper";
        data.item_cost = paper.cost_buy;
        data.cost_type = "buy";
        data.quantity = 1;
        data.total = (paper.cost_buy * data.quantity);
        data.user_type = "student";
        data.user_id = TokenService.getUserId();
        
        $http.post("http://localhost/bookstore/api.php/carts", data, TokenService.getConfig());
        
        $rootScope.$broadcast('cartUpdated');
    }
    
//    this.getCart_Id = function(){
//        return this.cart_Id;
//        console.log("Cart ID= " + this.cart_Id);
//    }
}]);

app.controller('RootController', ['$http', 'PanelService', 'TokenService', function($http, PanelService, TokenService){
    this.isActive = function(panel){
        return PanelService.isActive(panel);
    };
    
    this.setActive = function(clickedPanel){
        PanelService.setActive(clickedPanel);
    };
    
    this.isTokenType = function(tokenType) {
        return TokenService.isTokenType(tokenType);
    };
    
    this.getTokenType = function() {
        return TokenService.getTokenType();
    };
    
    this.getToken = function() {
        return TokenService.getToken();
    };
    
    PanelService.setActive('login');
}]);

app.controller('LoginController', ['$http', 'PanelService', 'TokenService', function($http, PanelService, TokenService){
    this.user = {};
    this.error = '';
    
    var store = this;
    
    this.authenticate = function(){
        $http.post('http://localhost/bookstore/api.php/tokens', store.user).success(function(data){
            if (data.token != null){
                // Login Success
                TokenService.setToken(data.token, data.tokenType, data.userId);
                if (data.tokenType == 'employee'){
                    PanelService.setActive('employees');
                } else {
                    PanelService.setActive('books');
                }
                console.log("Feedback:" + data.tokenType);
            } else {
                store.error = "Invalid Email or Password";
            }
        }).error(function(data){
            store.error = data;    
        });
    }
    
    this.logout = function(){
        
    }
//    this.logout = function(data){
//        $http.post('http://localhost/bookstore/api.php/tokens', store.user).success(function(data){})
//            store.remove(data.token);
//            store.remove(data.tokenType);
//            if (data.token == null && data.tokenType == ''){
//                PanelService.setActive('login');
//                console.log("You have succesfully been logged out.");
//            } else {
//                console.log(error);
//            }
//    }
}]);

app.controller('RegisterController',['$http','PanelService',function($http, PanelService){
   
    this.user = {};
    this.error = '';
    
    var store = this;
    
    this.authenticate = function(){
        $http.post('http://localhost/bookstore/api.php/tokens', store.user).success(function(data){
            if (data.token != null){
                PanelService.setActive('books')
            } else {
                store.error = "Invalid Email or Password";
            }
        }).error(function(data){
            store.error = data;
        });
    }
}]);

app.controller('EmployeesController',['$scope', 'EmployeesService', function($scope, EmployeesService){
    this.intro = {title: "Welcome to the Employees page", subtitle: "The Employees are displayed below."};

    var store = this;
    
    $scope.$on('tokenSet', function(){
        EmployeesService.getEmployees().success(function(data){
            store.employees = data;   
        });    
    });

}]);

app.controller('StudentsController',['$scope', 'StudentsService', function($scope, StudentsService){
    this.intro = {title: "Welcome to the Students page", subtitle: "The Students are displayed below."};

    var store = this;
    
    $scope.$on('tokenSet', function(){
        StudentsService.getStudents().success(function(data){
            store.students = data;   
        });    
    });

}]);

app.controller('BooksController',['$scope', 'BooksService', 'ShopService', function($scope, BooksService, ShopService){
    this.intro = {title: "Welcome to the books page", subtitle: "The Books are displayed below."};

    var store = this;
    
    $scope.$on('tokenSet', function(){
        BooksService.getBooks().success(function(data){
            store.books = data;
        });    
    });
    
    this.buyBook = function(book) {
        ShopService.buyBook(book);
    }
    
    this.rentBook = function(book){
        ShopService.rentBook(book);
    }
    
    this.removeBook = function(book){
        ShopService.removeBook(book);
    }

}]);

app.controller('JournalsController',['$scope', 'JournalsService', 'ShopService', function($scope, JournalsService, ShopService){
    this.intro = {title: "Welcome to the Journals page", subtitle: "The Journals are displayed below."};

    var store = this;
    
    $scope.$on('tokenSet', function(){
        JournalsService.getJournals().success(function(data){
            store.journals = data;   
        });    
    });
    
    this.buyJournal = function(journal) {
        ShopService.buyJournal(journal);
    }

}]);

app.controller('PapersController',['$scope', 'PapersService', 'ShopService', function($scope, PapersService, ShopService){
    this.intro = {title: "Welcome to the Papers page", subtitle: "The Papers are displayed below."};

    var store = this;
    
    $scope.$on('tokenSet', function(){
        PapersService.getPapers().success(function(data){
            store.papers = data;   
        });    
    });
    
    this.buyPaper = function(paper) {
        ShopService.buyPaper(paper);
    }

}]);

app.controller('ShopController',['$scope', 'ShopService', function($scope, ShopService){
    this.intro = {title: "Welcome to the Carts page", subtitle: "The carts are displayed below."};
    
    var store = this;
    
    $scope.$on('tokenSet', function(){
        ShopService.getCarts().success(function(data){
            store.carts = data;
        });
    });
    
        
    $scope.$on('cartUpdated', function(){
        ShopService.getCarts().success(function(data){
            store.carts = data;
        });  
    });
    
    this.removeItem = function(){
        ShopService.remove
    }
}]);
