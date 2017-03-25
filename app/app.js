

var app = angular.module('myApp', ['ngRoute']);
app.factory("services", ['$http', function($http) {
  var serviceBase = 'services/'
    var obj = {};
    obj.getcontactos = function(){
        return $http.get(serviceBase + 'contactos');
    }
    obj.getcontacto = function(intId){
        return $http.get(serviceBase + 'contacto?id=' + intId);
    }

    obj.insertcontacto = function (contacto) {
    return $http.post(serviceBase + 'insertcontacto', contacto).then(function (results) {
        return results;
    });
	};

	obj.updatecontacto = function (id,contacto) {
	    return $http.post(serviceBase + 'updatecontacto', {id:id, contacto:contacto}).then(function (status) {
	        return status.data;
	    });
	};

	obj.deletecontacto = function (id) {
	    return $http.delete(serviceBase + 'deletecontacto?id=' + id).then(function (status) {
	        return status.data;
	    });
	};

    return obj;
}]);

app.controller('listCtrl', function ($scope, services) {
    services.getcontactos().then(function(data){
        $scope.contactos = data.data;
    });
});

app.controller('editCtrl', function ($scope, $rootScope, $location, $routeParams, services, contacto) {
    var intId = ($routeParams.intId) ? parseInt($routeParams.intId) : 0;
    $rootScope.title = (intId > 0) ? 'Editar Contacto' : 'Agregar Contacto';
    $scope.buttonText = (intId > 0) ? 'Actualizar' : 'Guardar';
      var original = contacto.data;
      original._id = intId;
      $scope.contacto = angular.copy(original);
      $scope.contacto._id = intId;
      $scope.contacto.Telefono=parseInt($scope.contacto.Telefono);
      $scope.contacto.Celular=parseInt($scope.contacto.Celular);

      $scope.isClean = function() {
        return angular.equals(original, $scope.contacto);
      }

      $scope.deletecontacto = function(contacto) {
        $location.path('/');
        if(confirm("Desea eliminar el contacto con Id: "+$scope.contacto._id)==true)
        services.deletecontacto(contacto.intId);
      };

      $scope.savecontacto = function(contacto) {
        $location.path('/');
        if (intId <= 0) {
            services.insertcontacto(contacto);
        }
        else {
            services.updatecontacto(intId, contacto);
        }
    };
});

app.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/', {
        title: 'contactos',
        templateUrl: 'partials/contactos.html',
        controller: 'listCtrl'
      })
      .when('/edit-contacto/:intId', {
        title: 'Edit contactos',
        templateUrl: 'partials/edit-contacto.html',
        controller: 'editCtrl',
        resolve: {
          contacto: function(services, $route){
            var intId = $route.current.params.intId;
            return services.getcontacto(intId);
          }
        }
      })
      .otherwise({
        redirectTo: '/'
      });
}]);
app.run(['$location', '$rootScope', function($location, $rootScope) {
    $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
        $rootScope.title = current.$$route.title;
    });
}]);
