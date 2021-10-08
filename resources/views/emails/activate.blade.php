<div>
	<style>
		/* vietnamese */
		@font-face {
		  font-family: 'Dancing Script';
		  font-style: normal;
		  font-weight: 400;
		  src: url(https://fonts.gstatic.com/s/dancingscript/v16/If2cXTr6YS-zF4S-kcSWSVi_sxjsohD9F50Ruu7BMSo3Rep8ltA.woff2) format('woff2');
		  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
		}
		/* latin-ext */
		@font-face {
		  font-family: 'Dancing Script';
		  font-style: normal;
		  font-weight: 400;
		  src: url(https://fonts.gstatic.com/s/dancingscript/v16/If2cXTr6YS-zF4S-kcSWSVi_sxjsohD9F50Ruu7BMSo3ROp8ltA.woff2) format('woff2');
		  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
		}
		/* latin */
		@font-face {
		  font-family: 'Dancing Script';
		  font-style: normal;
		  font-weight: 400;
		  src: url(https://fonts.gstatic.com/s/dancingscript/v16/If2cXTr6YS-zF4S-kcSWSVi_sxjsohD9F50Ruu7BMSo3Sup8.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
		}
		/* vietnamese */
		@font-face {
		  font-family: 'Dosis';
		  font-style: normal;
		  font-weight: 400;
		  src: url(https://fonts.gstatic.com/s/dosis/v19/HhyaU5sn9vOmLzlnC_W6EQ.woff2) format('woff2');
		  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
		}
		/* latin-ext */
		@font-face {
		  font-family: 'Dosis';
		  font-style: normal;
		  font-weight: 400;
		  src: url(https://fonts.gstatic.com/s/dosis/v19/HhyaU5sn9vOmLzlmC_W6EQ.woff2) format('woff2');
		  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
		}
		/* latin */
		@font-face {
		  font-family: 'Dosis';
		  font-style: normal;
		  font-weight: 400;
		  src: url(https://fonts.gstatic.com/s/dosis/v19/HhyaU5sn9vOmLzloC_U.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
		}
		/* vietnamese */
		@font-face {
		  font-family: 'Dosis';
		  font-style: normal;
		  font-weight: 500;
		  src: url(https://fonts.gstatic.com/s/dosis/v19/HhyaU5sn9vOmLzlnC_W6EQ.woff2) format('woff2');
		  unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;
		}
		/* latin-ext */
		@font-face {
		  font-family: 'Dosis';
		  font-style: normal;
		  font-weight: 500;
		  src: url(https://fonts.gstatic.com/s/dosis/v19/HhyaU5sn9vOmLzlmC_W6EQ.woff2) format('woff2');
		  unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
		}
		/* latin */
		@font-face {
		  font-family: 'Dosis';
		  font-style: normal;
		  font-weight: 500;
		  src: url(https://fonts.gstatic.com/s/dosis/v19/HhyaU5sn9vOmLzloC_U.woff2) format('woff2');
		  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
		}
	</style>
<div style="background:#31708E;text-align:center;color:#fff;">
	<h1 style="font-family:'Dancing Script', cursive;font-size:36pt;text-shadow: 1.5px 1.5px #333;color:#5bc1ab;">{{ env('APP_NAME') }}</h1>
	<div style="font-family: 'Dosis', sans-serif; font-weight: 400;font-size: 13pt;">
		a history of the surname family
	</div>
</div>
<div style="background:#8fc1e3;padding:10px;color:#000;">
	Greetings,
	<br/><br/>
	Please follow the link below to activate account.
	<br/><br/>
	{{ asset('index.php/activate?tk=' . $token) }}
	<br/><br/>
	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam pharetra, tellus sit amet congue vulputate, nisi erat iaculis nibh, vitae feugiat sapien ante eget mauris. Pellentesque ac felis tellus. Aenean sollicitudin imperdiet arcu, vitae dignissim est posuere id. Duis placerat justo eu nunc interdum ultrices. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam pharetra, tellus sit amet congue vulputate, nisi erat iaculis nibh, vitae feugiat sapien ante eget mauris. Pellentesque ac felis tellus. Aenean sollicitudin imperdiet arcu, vitae dignissim est posuere id. Duis placerat justo eu nunc interdum ultrices.
</div>
<div style="padding:15px;background:#31708E;text-align:center;font-size:12px;">
	<p>This site is powered by The Next Generation of Genealogy Sitebuilding v 13.0.4, written by Darrin Lythgoe &copy; 2001-2021.</p>
	<p>Maintained by Maram Test</p>
</div>
</div>