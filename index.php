<html>
	<head>
		<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
		<script>
			function Popup(text, parent) {
				const popup = $("<div class='pop'></div>").append(
					$("<span class='pop-text'></span>").text(text)
				);
				$(parent).append(popup);
				popup.animate = function() {
					$(this).animate({"opacity": 0 }, function(){
						$(this).remove();
					});
				}
				return popup;
			}

			function fetch() {
				$("#result").html("").addClass("hidden");
				$("#loading").removeClass("hidden");
				$("#copy").addClass("hidden");
				$.get("get.php?url=" + $("#url").val(), function(data){
					$("#result").removeClass("hidden");
					$("#loading").addClass("hidden");
					$("#copy").removeClass("hidden");
					$("#result").text(data);
				});
			}
			
			function copyToClipboard(payload) {
				navigator.clipboard.writeText(payload);
				const copypopup = new Popup("Copied!", event.target);
				copypopup.animate();
			}
		</script>
		<style>
			body {
				height: 100vh;
				margin: 0;
				padding: 1rem;
				display: flex;
				flex-direction: column;
				box-sizing: border-box;
			}

			input, button {
				padding: 0.4rem;
			}

			.hidden {
				display: none;
			}

			.pop {
			position: absolute;
			display: flex;
			justify-content: center;
			width: 100%;
			bottom: 100%;
			z-index: 3;
			margin-bottom: 0.5em;
			}

			.pop-text {
			background: var(--black);
			color: var(--softwhite);
			}

			#copy {
				position: relative;
			}

			#result {
				overflow-y: scroll;
				flex-grow: 1;
				background-color: #eee;
			}
		</style>
	</head>
	<body>
		<input type="text" id="url" placeholder="Bandcamp album URL"></input>
		<button onclick="fetch()">Fetch</button>
		<div id="loading" class="hidden">
			Fetching...this may take a while...
		</div>
		<pre id="result" class="hidden">
		</pre>
		<button id="copy" class="hidden" onclick="copyToClipboard($('#result').text())">Copy to clipboard</button>
	</body>
</html>