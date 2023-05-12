<?php 

        $userName = $_COOKIE['Username'];
echo 
"<script>
        
        $(document).ready(function() {  
            $('#editBioInput').hide();
            $('#saveBioChanges').hide();
            $('#successBio').hide();

            $('#editBioIcon').click(function() {

                if($('#editBioIcon').hasClass('bioShown')){

                    $('#bioText').hide();
                    $('#edit-text').hide();
                    $('#editBioInput').show();
                    $('#saveBioChanges').show();
                    $('#editBioIcon').removeClass('bioShown');
                    
                    $('#bioIconSVG').removeClass('bi-pencil');
                    $('#bioIconSVG').addClass('bi-x-square-fill');

                    $('#bioIconPath').attr('d','M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z');

                } else {
                    $('#bioText').show();
                    $('#edit-text').show();
                    $('#editBioIcon').show();
                    $('#editBioInput').hide();
                    $('#saveBioChanges').hide();
                    $('#editBioIcon').addClass('bioShown');

                    $('#bioIconSVG').removeClass('bi-x-square-fill');
                    $('#bioIconSVG').addClass('bi-pencil');

                    $('#bioIconPath').attr('d','M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z');
                }
                    
            });

            $('#saveBioChanges').click(function() {
                var bio=document.getElementById('editBioInput').value;
                
                $.ajax({
                url: '../process_files/bioUpdate.php',
                type: 'POST',
                data: jQuery.param({ biography: bio, username: <?php echo '\'$userName\''; ?>},) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    // alert(response);
                    $('#editBioIcon').click();
                    $('#successBio').show();
                    $('#bioText').text(bio)
                    setTimeout(function() { $('#successBio').hide(); }, 2000);
                },
                error: function (xhr, status, error) {
                    var err = JSON.parse(xhr.responseText);
                    alert(err.Message);
                }
                }); 
            });

            function changeSelectionsFavAlbum(favouriteAlbum) {
                if(favouriteAlbum == 'No albums with that name') {
                    $('#returnedAlbumSelection').text(favouriteAlbum);
                } else {
                    
                    $('#returnedAlbumSelection').text(favouriteAlbum);
                    $('#returnedAlbumSelection').removeAttr('disabled');
                }
            };

            function changeSelectionsOwnedAlbum(favouriteAlbum) {
                if(favouriteAlbum == 'No albums with that name') {
                    $('#returnedOwnedAlbumSelection').text(favouriteAlbum);
                } else {
                    
                    $('#returnedOwnedAlbumSelection').text(favouriteAlbum);
                    $('#returnedOwnedAlbumSelection').removeAttr('disabled');
                }
            };

            $('#userFavSearchButton').click(function(e) {
                
                var favAlbumSearch = $('#userFavSearch').val();

                $.ajax({
                url: '../process_files/userFavSearchButton.php',
                type: 'POST',
                data: jQuery.param({ favAlbum: favAlbumSearch, username: <?php echo '\'$userName\''; ?>},) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    // alert(response);
                    var favAlbumReturned = response;
                    
                    changeSelectionsFavAlbum(favAlbumReturned);
                    
                    
                    // alert(favAlbumReturned);

                },
                error: function () {
                    alert('error');
                }
                }); 
            });

            $('#addAlbumToFavs').click(function(e) {
                
                var favAlbumReturned = $('#returnedAlbumSelection').val();

                $.ajax({
                url: '../process_files/addAlbumToFavs.php',
                type: 'POST',
                data: jQuery.param({ favAlbumReturn: favAlbumReturned, username: <?php echo '\'$userName\''; ?>},) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {

                    window.location.reload();

                },
                error: function () {
                    alert('error');
                    
                }
                }); 
            });

            $('#favAlbumEditButton').click(function(e) {

                    var select = document.getElementById('FavAlbumEditSelect');
                    var option = select.options[select.selectedIndex];

                    var albumFavEdit = select.value;

                    // alert(albumFavEdit);

                    $.ajax({
                        url: '../process_files/favAlbumEditButton.php',
                        type: 'POST',
                        data: jQuery.param({ albumFavForEdit: albumFavEdit, username: <?php echo '\'$userName\''; ?>},) ,
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function (response) {

                            window.location.reload();

                        },
                        error: function () {
                            alert('error');
                            
                        }
                    }); 
                
            });

            $('#userOwnedSearchButton').click(function(e) {
                
                var ownedAlbumSearch = $('#userOwnedSearch').val();

                $.ajax({
                url: '../process_files/userOwnedSearchButton.php',
                type: 'POST',
                data: jQuery.param({ ownedAlbum: ownedAlbumSearch, username: <?php echo '\'$userName\''; ?>},) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    // alert(response);
                    var ownedAlbumReturned = response;
                    
                    changeSelectionsOwnedAlbum(ownedAlbumReturned); 
                    
                    
                    // alert(favAlbumReturned);

                },
                error: function () {
                    alert('error');
                }
                }); 
            });

            $('#addAlbumToOwned').click(function(e) {
                
                var ownedAlbumReturned = $('#returnedOwnedAlbumSelection').val();

                // alert(ownedAlbumReturned);
                
                $.ajax({
                url: '../process_files/addAlbumToOwned.php',
                type: 'POST',
                data: jQuery.param({ ownedAlbumToAdd: ownedAlbumReturned, username: <?php echo '\'$userName\''; ?>},) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function (response) {
                    // alert(response);
                    // var favAlbumReturned = response;
                    // changeSelections(favAlbumReturned);
                    // alert(favAlbumReturned);
                    window.location.reload();

                },
                error: function () {
                    alert('error');
                    
                }
                }); 
            });
            
            $('#ownedAlbumEditButton').click(function(e) {
                var select = document.getElementById('ownedAlbumEditSelect');
                var option = select.options[select.selectedIndex];

                var albumOwnedEdit = select.value;

                // alert(albumOwnedEdit);

                $.ajax({
                    url: '../process_files/ownedAlbumEdit.php',
                    type: 'POST',
                    data: jQuery.param({ albumOwnedForEdit: albumOwnedEdit, username: <?php echo '\'$userName\''; ?>},) ,
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function (response) {

                        window.location.reload();

                    },
                    error: function () {
                        alert('error');
                        
                    }
                }); 
                
            });


        });

        
        
</script>";
