var sqlData = null;

setInterval(function() {
    if(Notification.permission!=='default'){
        $.get('./php/user-assistance-notif.php', function(data){
            sqlData = JSON.parse(data);
            for(var i=0;i<sqlData.length;i++){
                var notify;
                
                if(sqlData[i].new_reports > 0){
                    notify= new Notification('Notification from Marikina Health & Safety App',{
                        'body': 'You have ' + sqlData[i].new_reports + ' new user assistance report(s).',
                        'icon': 'images/marikina-city-seal-big.jpg'
                    });

                    notify.onclick = function(){
                        alert(this.tag);
                    }
                }
            }
        });	

    }else{
        alert('Please allow the notification first.');
    }	
}, 10 * 60 * 1000); // EVERY 10 mins