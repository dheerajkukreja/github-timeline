const REGISTER_URL = "user.php"
$(document).ready(function(){
	$("#register").click(function(){
		subscribe()
	})
	function subscribe() {
		let url = REGISTER_URL,
		formData = $('#subscribe_frm').serializeArray();
		formData.push({ name: "action", value: "subscribe"});
		console.log(formData)
		$("#error").hide();
		$.ajax({
            type: "POST",
            url: url,
            data: formData,
            success: function (response) {
                response = $.parseJSON(response);
				console.log(response.status)
				if(response.status) {
					$("#container").addClass('right-panel-active');
				} else{
					$("#error_all").html(response.msg);
					$("#error").show();
					// alert(response.msg)
				}
            }
        });
	}

})









const backButton = document.getElementById('back');
// const registerButton = document.getElementById('register');
// const container = document.getElementById('container');

// registerButton.addEventListener('click', () => {
// 	container.classList.add("right-panel-active");
// });

backButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
});