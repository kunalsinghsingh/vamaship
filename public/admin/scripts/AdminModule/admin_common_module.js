$(document).ready(function ()
{
   
});

function delete_(id, url) {
    bootbox.confirm("Are you sure?", function (result) {
        if (result) {
            window.location.href = SITE_URL + url + id;
        }
    });
}

