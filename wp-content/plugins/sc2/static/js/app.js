sc2.init({
  app: 'steemian.app',
  callbackURL: 'http://steemian.io/callback',
  scope: ['vote', 'comment']
});

angular.module('app', ['ipCookie'])
  .config(['$locationProvider', function ($locationProvider) {
    $locationProvider.html5Mode({
      enabled: true,
      requireBase: true,
      rewriteLinks: false
    });
  }])
  .controller('Main', function($scope, ipCookie) {
    $scope.loading = false;
    $scope.parentAuthor = 'skenan';
    $scope.parentPermlink = 'steem-connect-v2';    
    $scope.accessToken = ipCookie('st_access_token');
    $scope.loginURL = sc2.getLoginURL();

    if ($scope.accessToken) {
      sc2.setAccessToken($scope.accessToken);
      sc2.me(function (err, result) {
        console.log('/me', err, result);
        if (!err) {
          $scope.user = result.account;
          $scope.metadata = JSON.stringify(result.user_metadata, null, 2);
          $scope.$apply();
        }
      });
    }

    $scope.isAuth = function() {
      return !!$scope.user;
    };

    $scope.loadComments = function() {
      steem.api.getContentReplies($scope.parentAuthor, $scope.parentPermlink, function(err, result) {
        if (!err) {
          $scope.comments = result.slice(-5);
          $scope.$apply();
        }
      });
    };

    $scope.comment = function() {
      $scope.loading = true;
      var permlink = steem.formatter.commentPermlink($scope.parentAuthor, $scope.parentPermlink);
      sc2.comment($scope.parentAuthor, $scope.parentPermlink, $scope.user.name, permlink, '', $scope.message, '', function(err, result) {
        console.log(err, result);
        $scope.message = '';
        $scope.loading = false;
        $scope.$apply();
        $scope.loadComments();
      });
    };

    $scope.vote = function(author, permlink, weight) {
      sc2.vote($scope.user.name, author, permlink, weight, function (err, result) {
        if (!err) {
          alert('You successfully voted for @' + author + '/' + permlink);
          console.log('You successfully voted for @' + author + '/' + permlink, err, result);
          $scope.loadComments();
        } else {
          console.log(err);
        }
      });
    };

    $scope.updateUserMetadata = function(metadata) {
      sc2.updateUserMetadata(metadata, function (err, result) {
        if (!err) {
          alert('You successfully updated user_metadata');
          console.log('You successfully updated user_metadata', result);
          if (!err) {
            $scope.user = result.account;
            $scope.metadata = JSON.stringify(result.user_metadata, null, 2);
            $scope.$apply();
          }
        } else {
          console.log(err);
        }
      });
    };

    $scope.logout = function() {
      //delete the cookie when logout
      //ipCookie.remove('st_access_token');

      sc2.revokeToken(function (err, result) {
        ipCookie.remove('st_access_token', {path:'/'});
        console.log('You successfully logged out', err, result);
        delete $scope.user;
        delete $scope.accessToken;
        $scope.$apply();
      });
    };
  })
  .controller('SetCookies', ['$scope', '$location', 'ipCookie', function($scope, $location, ipCookie) {
    $scope.loading = false;    
    $scope.accessToken = $location.search().access_token;
    $scope.expiresIn = $location.search().expires_in;

    if ($scope.accessToken) {
      sc2.setAccessToken($scope.accessToken);
      //get the details of an account
      //set the cookie
      ipCookie('st_access_token', $scope.accessToken, 
            {expirationUnit: 'seconds', 
            expires: $scope.expiresIn * 1,
            path: '/'});
      sc2.me(function (err, result) {
        console.log('/me', err, result);
        if (!err) {
          $scope.user = result.account;
          $scope.metadata = JSON.stringify(result.user_metadata, null, 2);
          $scope.$apply();
        }
      });
    }
    $scope.isAuth = function() {
      return !!$scope.user;
    };
  }]);
