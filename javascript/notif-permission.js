if(!window.Notification){
    alert('Not supported');
}else{
    Notification.requestPermission().then(function(p){
    });
}