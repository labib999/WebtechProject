function searchEvents(){
    var search = document.getElementById("eventSearch").value;
    var xhr = new XMLHttpRequest();

    xhr.open("GET", "../../api/search-events.php?search=" + search, true);

    xhr.onload = function(){
        if (xhr.status == 200) {
            var events = JSON.parse(xhr.responseText);
            var output = "";

            if (events.length > 0) {
                for (var i = 0; i < events.length; i++) {
                    output += '<div class="event-list-card">';
                    output += '<div class="event-card-image"></div>';
                    output += '<div class="event-card-body">';
                    output += '<span class="event-tag">' + events[i].category_name + '</span>';
                    output += '<h3>' + events[i].title + '</h3>';
                    output += '<p>' + events[i].description + '</p>';
                    output += '<div class="event-meta">';
                    output += '<span>' + events[i].event_datetime + '</span>';
                    output += '<span>' + events[i].venue_name_override + '</span>';
                    output += '</div>';
                    output += '<div class="event-price">From ৳' + events[i].min_price + '</div>';
                    output += '<div class="event-actions">';
                    output += '<a href="event-details.php?id=' + events[i].id + '" class="event-btn">View Details</a>';
                    output += '</div>';
                    output += '</div>';
                    output += '</div>';
                }
            } else {
                output = '<div class="content-card"><h2>No events found</h2><p>Try another event name.</p></div>';
            }

            document.getElementById("eventsBox").innerHTML = output;
        }
    };

    xhr.send();
}