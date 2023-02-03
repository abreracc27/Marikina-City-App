var sqlData2 = null;

setInterval(function() {
    if(Notification.permission!=='default'){
        $.get('./php/messages-notif.php', function(data2){
            sqlData2 = JSON.parse(data2);
            for(var i=0;i<sqlData2.length;i++){
                var notify2;
                
                if(sqlData2[i].new_messages > 0){
                    notify2= new Notification('Notification from Marikina Health & Safety App',{
                        'body': 'You have ' + sqlData2[i].new_messages + ' new messages.',
                        'icon': 'images/marikina-city-seal-big.jpg'
                    });

                    notify2.onclick = function(){
                        alert(this.tag);
                    }
                }
            }
        });	

    }else{
        alert('Please allow the notification first.');
    }	
}, 10 * 60 * 1000); // EVERY 10 mins