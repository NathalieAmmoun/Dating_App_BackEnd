$(document).ready(getUnapprovedPictures);

function getUnapprovedPictures(){
    unApprovedPicturesAPI().then(pictures=>{
        console.log(pictures);
         $.each(pictures, function(index, picture){
             $(".pics_div").append("<div id='picture_"+picture.id+"' class='card'><img class='card-img-top' src='http://127.0.0.1:8000/storage/"+picture.picture_url+"' alt='Card image cap'><div class='card-body'><button type='button' onclick='approve("+picture.id+");' class='btn btn-success'>Approve</button>&nbsp<button type='button' onclick='deletePic("+picture.id+");' class='btn btn-danger'>Decline</button></div></div>");
    });
}).catch(error => {
    console.log(error.message);
});

}


async function unApprovedPicturesAPI(){
    var access_token = $("#access_token").val();
    var authorization = "bearer "+access_token;
    var accept = "application/json";
    const response = await fetch("http://127.0.0.1:8000/api/auth/UnapprovedPics", {
        method: 'GET',
        headers: {
            'Authorization': authorization,
            'Accept': accept
        }
    });
    
    if(!response.ok){
        const message = "ERROR OCCURED";
        throw new Error(message);
    }
    
    const pictures = await response.json();
    return pictures;
}




function approve(picture_id){
    approveAPI(picture_id).then(approve_response=>{
         $("#picture_"+picture_id).hide();
}).catch(error => {
    console.log(error.message);
});
}

async function approveAPI(picture_id){
    var access_token = $("#access_token").val();
    var authorization = "bearer "+access_token;
    var accept = "application/json";
    const data = {picture_id : picture_id};
    const response = await fetch("http://127.0.0.1:8000/api/auth/PictureApproval", {
        method: 'POST',
        data: {
            "picture_id": picture_id
        },
        headers: {
            'Authorization': authorization,
        },

    });
    
    if(!response.ok){
        const message = "ERROR OCCURED";
        throw new Error(message);
    }
    
    const approve_response = await response.json();
    return approve_response;
}


function deletePic(picture_id){
    deletePicAPI(picture_id).then(delete_response=>{
         $("#picture_"+picture_id).hide();
}).catch(error => {
    console.log(error.message);
});
}

async function deletePicAPI(picture_id){
    var access_token = $("#access_token").val();
    var authorization = "bearer "+access_token;
    var accept = "application/json";
    const data = {picture_id : picture_id};
    const response = await fetch("http://127.0.0.1:8000/api/auth/PictureRejection", {
        method: 'POST',
        data: {
            "picture_id": picture_id
        },
        headers: {
            'Authorization': authorization,
        },

    });
    
    if(!response.ok){
        const message = "ERROR OCCURED";
        throw new Error(message);
    }
    
    const delete_response = await response.json();
    return delete_response;
}

