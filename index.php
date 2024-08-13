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
				$.post("get.php",
				{
					url: $("#url").val(),
					one_musician: $("#musician-check").prop("checked"),
					track_artist: $("#artist-check").prop("checked") ? $("#track-artist").val() : "",
					cover_artist: $("#cover-artist").val()
				},
				function(data){
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
			@keyframes spin {
				0% {
					transform: rotate(0deg);
				}
				50% {
					transform: rotate(180deg);
				}
				100% {
					transform: rotate(360deg);
				}
			}

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
				padding: 0.5rem;
				overflow-y: scroll;
				flex-grow: 1;
				background-color: #eee;
			}
			
			#hourglass {
				display: inline-block;
				animation-name: spin;
				animation-duration: 1.5s;
				animation-iteration-count: infinite;
			}

			.hide {
				display: none;
			}

			.row {
				display: flex;
				flex-wrap: wrap;
				align-items: center;
				gap: 2rem;
				padding: 0.4rem 0;
			}
		</style>
	</head>
	<body>
		<div>Bandcamp YAML - Takes a Bandcamp album URL and generates a YAML file skeleton, for use with the <a href="https://github.com/hsmusic">HS Music Wiki</a> repo. Note that only public albums and tracks can be accessed.</div>
		<input type="text" id="url" placeholder="Bandcamp album URL"></input>
		
		<div class="row">
			<input type="text" id="cover-artist" placeholder="Album cover artist name" autocomplete="off">

			<div>
				<label for="musician-check">Same musician made all music?</label>
				<input id="musician-check" type="checkbox" autocomplete="off">
			</div>
			<div>
				<label for="artist-check">Same artist made all art?</label>
				<input id="artist-check" type="checkbox" onchange="$('#track-artist').toggleClass('hide', !this.checked)" autocomplete="off">
				<input type="text" id="track-artist" class="hide" placeholder="Track artist name">
			</div>
		</div>
		
		<button onclick="fetch()">Fetch</button>
		<div id="loading" class="hidden">
			Fetching...this may take a while... <span id="hourglass">⏳</span>
		</div>
		<pre id="result" class="hidden">
		</pre>
		<button id="copy" class="hidden" onclick="copyToClipboard($('#result').text())">Copy to clipboard</button>
	</body>
</html>