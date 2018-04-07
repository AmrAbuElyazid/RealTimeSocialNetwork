var app = angular.module('facebookApp', []);

app.run(function ($http) {
})

app.controller('chatCtrl', function ($scope, $http) {
	$scope.msg = '';
	$scope.msgs = [];
	$scope.isMeTyping = false;

	$http.post('/chat/new_thread', {id: user_id}).then(function (res) {
		$scope.threadId = res.data;
	    var socket = io('http://localhost:3000');
        // socket.join($scope.thread_id);

	    socket.on("channel-name:App\\Events\\ChatEvent\\Room\\" + $scope.threadId, function(message){
	    	$scope.msgs.push(message);
	    	if(message.from_id == user_id) {
	    		$scope.isHimTyping = false;
	    	}
	    	$scope.$digest();
			$scope.scrollToBottom();
	    });

	    socket.on("channel-name:App\\Events\\ChatEvent\\Room\\Typing\\" + $scope.threadId, function(message){
	    	if(message == null) {
	    		$scope.isHimTyping = false;
	    		$scope.$digest();
				$scope.scrollToBottom();
	    	}else{
	    		if(message == user_id) {
		    		$scope.isHimTyping = true;
		    		$scope.$digest();
					$scope.scrollToBottom();
	    		}
	    	}
	    });
		$scope.typing = function () {
			var flag = Boolean($scope.msg.length);
			// if($scope.isMeTyping != flag) {
				$http.post('/chat/typing', {typing: flag, thread_id: $scope.threadId}).then(function () {
					$scope.isTyping = flag;
				})
			// }
		}
	});


	$http.get('/chat/get?user_id=' + user_id).then(function (res) {
		$scope.msgs = res.data;
		console.log(res)
	})

	$scope.sendMessage = function (to_id) {
		if($scope.msg.length) {
			let message = {
	        	to_id: to_id,
	        	text: $scope.msg
	        }
	        $http.post('/chat/send', message).then(function () {
	        	$scope.msg = '';
	        })
		}
	}

	$scope.scrollToBottom = function (time) {
		if (!time) time = 0;
		setTimeout(function () {
			$("#chat-body").animate({ scrollTop: $('#chat-body').prop("scrollHeight")}, 500);
		}, time)
	}

});

app.controller('postsController', function ($scope, $http) {

	$scope.getPosts = function (fromInterval) {
		$http.get('posts/get').then(function (results) {

			$scope.myPosts = results.data.myPosts;
			$scope.friendsPosts = results.data.friendsPosts;

			for (var i = $scope.friendsPosts.length - 1; i >= 0; i--) {
				for (var j = $scope.friendsPosts[i].length - 1; j >= 0; j--) {
					$scope.myPosts.unshift($scope.friendsPosts[i][j]);
				}
			}

		})
	}


	$scope.newPost = function (username) {
		if($scope.post) {
			$http.post('posts/new', {
				post: $scope.post
			}).then(function (results) {
				if($scope.myPosts.length) {
					$scope.myPosts.unshift(results.data);
				}else{
					$scope.myPosts = [];
					$scope.myPosts.unshift(results.data);
				}

				$scope.post = '';

			})
		}

	}


	$scope.comments = [];

	$scope.newComment = function (post, index) {
		if($scope.comments[post.id]) {
			$http.post('posts/comment', {
				comment: $scope.comments[post.id],
				post_id: post.id
			}).then(function (results) {
				$scope.comments[post.id] = '';
				$scope.myPosts[index] = results.data;
			})
		}
	}

	$scope.like = function (post, index) {
		$http.post('/posts/like', {
			post_id: post.id
		}).then(function (results) {
			$scope.myPosts[index] = results.data;
		})
	}

});

app.controller('friendshipsController', function ($scope, $http) {

	// check if friends
	$http.post('/friendships/checkIfFriends', {
		user_id: user_id
	}).then(function (results) {
		$scope.friends = results.data.state;
	})

	// check if i have a friend request from him
	$http.post('/friendships/checkIfHaveRequestFrom', {
		user_id: user_id
	}).then(function (results) {
		$scope.haveRequestFrom = results.data.state;
	})

	// check if he sent me a friend request
	$http.post('/friendships/checkIfSentRequestTo', {
		user_id: user_id
	}).then(function (results) {
		$scope.sentRequestTo = results.data.state;
	})

	// send friend request
	$scope.sendFriendRequestTo = function () {
		$http.post('/friendships/sendFriendRequestTo', {
			user_id: user_id
		}).then(function (results) {
			$scope.sentRequestTo = true;
		})
	}

	// accept friend request
	$scope.acceptFriendRequest = function () {
		$http.post('/friendships/acceptFriendRequest', {
			user_id: user_id
		}).then(function () {
			$scope.friends = true;
		})
	}

	// deny friend request
	$scope.denyFriendRequest = function () {
		$http.post('/friendships/denyFriendRequest', {
			user_id: user_id
		}).then(function () {
			$scope.haveRequestFrom = false;
		})
	}

	// unfriend
	$scope.unfriend = function () {
		$http.post('/friendships/unfriend', {
			user_id: user_id
		}).then(function () {
			$scope.friends = false;
			$scope.haveRequestFrom = false;
		})
	}

});
