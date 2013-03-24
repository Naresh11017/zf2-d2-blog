function replyToComment(id)
{
    document.getElementById('parentId').value = id;
    window.location.hash = 'parentId';
}

function submitSearch()
{
    var form = document.getElementById('searchform');
    form.submit();
}

$(document).ready(function() {
    
    $("#searchInput").hover(
        function() {
            if($(this).attr('value') == 'Search...') {
                $(this).attr('value', '');
                $(this).focus();
            }
        },
        function() {
            if($(this).attr('value') == '') {
                $(this).attr('value', 'Search...');
            }
        }
    );

    $("#menu ul li a").hover(
        function() {
            $(this).stop(true, true).animate({backgroundColor: '#434b57'}, 400)
        },
        function() {
            if($(this).hasClass("selected")) {
                $(this).stop(true, true).animate({backgroundColor: '#333333'}, 400)
            } else {
                $(this).stop(true, true).animate({backgroundColor: '#383d44'}, 400)
            }
        }
    );

});