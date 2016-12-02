<?php


add_action('admin_menu', 'adding_settings_pagecfb'); 

function adding_settings_pageCFB(){
    add_options_page('Facebook Settings', 'Facebook Settings', 'manage_options', 'wp-facebook-comments', 'settings_pageCFB');
    add_plugins_page('Facebook Settings', 'Facebook Settings', 'manage_options', 'wp-facebook-comments', 'settings_pageCFB');
    add_action('admin_init', 'register_settingsCFB');
}

function register_settingsCFB() {
    register_setting('cfb_globalform', 'cfb_global');
    register_setting('cfb_commentform', 'cfb_com');
    register_setting('cfb_likeform', 'cfb_like');
    register_setting('cfb_notform', 'cfb_not');
    register_setting('cfb_ogform', 'cfb_og');
}
    
function settings_pageCFB() {
    ?>
<style type='text/css'>
h3 { padding: 10px;  }
.fbds {padding: 5px 0 15px 15px; }
.in1{ width: 300px; }
.in2{width: 150px; }
.copr { font-weight: bold; line-height: 15px }
.note { color: #B93217; font-weight: bold; }

</style>
    <div class='wrap'>
    <?php
    /* =======================================
    //      Global Settings 
    //======================================== */ ?>
        <div class='postbox'>
            <h3>Global Settings</h3>
            <div class='fbds'>
                <form method='post' action='options.php'>
                        <?php settings_fields('cfb_globalform'); ?>
                        <?php $cfb_global = get_option('cfb_global'); ?>
<!-- <span class='note'>NOTE:<br/>
If you don't understand any of these options, please visit <a href="http://hasnath.net/total-facebook-plugin-for-wordpress.php">Official Plugins Page</a>  to learn more..    </span><br/><br/>-->


                 <input type='checkbox' name="cfb_global[includesdk]" <?php checked($cfb_global['includesdk'], 'on'); ?> />
                 <label for='cfb_global[includesdk]'>Include Facebook JS SDK (*required , if not already included manually or by another plugin, select 

it)</label><br/><br/>
                <table><tr>
                <td>Facebook App ID:</td>
                 <td><input type='text' class='in1' name='cfb_global[appid]' value='<?=$cfb_global['appid']?>' /> (whats this? visit <a href="http://hasnath.net/total-facebook-plugin-for-wordpress.php" target="_blank">Total Facebook</a> for an explanation)</td></tr>
                <?php if(strlen($cfb_global['lang']) < 2) $cfb_global['lang'] = "en_US"; ?>
               <tr><td> Language:</td>
                 <td><input type='text' class='in1' name='cfb_global[lang]' value='<?=$cfb_global['lang']?>' /> (en_US for English)
</td></tr>
                    <tr>
                        <td><label for='cfb_global[all]'>Show Like on all pages</label></td>
                        <td><input type='checkbox' name="cfb_global[all]" <?php checked($cfb_global['all'], 'on'); ?> /></td>
                  	</tr>
</table>
                <br/><br/>
                <input  type="submit" class="button-primary" value="<?php _e('Update Global Settings'); ?>" />
    
                </form>
            </div>
         </div> <!-- postbox -->
         <?php 
         /*================================
         //             Comment Settings
         //================================ */ ?>
         <div class='postbox'>
            <h3>Facebook Comment Settings</h3>
            <div class='fbds'>
                <form method='post' action='options.php'>
                        <?php settings_fields('cfb_commentform'); ?>
                        <?php $cfb_com = get_option('cfb_com'); ?>
                <table>
                    <tr>
                        <td>Position of comment box: </td>
                        <td>
                        <select name='cfb_com[pos]' class='in1'>' 
                            <option value='before_wp' <?=selected($cfb_com['pos'], 'before_wp')?> >Before Wordpress Comments</option>
                            <option value='after_wp' <?=selected($cfb_com['pos'], 'after_wp')?> > After Wordpress Comments </option>
                            <option value='after_form' <?=selected($cfb_com['pos'], 'after_form')?> >After Wordpress Comment Form</option>
                       </select>
                       </td>
                    </tr>
                    <tr>
                        <td>Options: </td>
                        <td>
                        <select name='cfb_com[option]' class='in1'>
                            <option value='individual' <?=selected($cfb_com['option'], 'individual')?> >Individual Setting is the best</option>
                            <option value='enable_all' <?=selected($cfb_com['option'], 'enable_all')?> >Enable Comment for all pages/posts</option>
                            <option value='disable_all' <?=selected($cfb_com['option'], 'disable_all')?> >Disable Comment for all pages/posts</option>
                            <option value='enpage' <?=selected($cfb_com['option'], 'enpage')?> >Enable for all pages</option>
                            <option value='enpost' <?=selected($cfb_com['option'], 'enpost')?> >Enable for posts</option>
                       </select> when enabled/disabled for one type, individual setting will work for another
                       </td>
                       </tr>
                       <tr>
                            <td>
                   <?php if(strlen($cfb_com['width']) < 2) $cfb_com['width'] = 470; ?>
               <tr><td>Comment Box Width:</td>
                 <td><input type='text' class='in2' name='cfb_com[width]' value='<?=$cfb_com['width']?>' /> (default: 470)</td>
                 </tr>
                 <tr>
                          <td>
                   <?php if(strlen($cfb_com['numpost']) < 2) $cfb_com['numpost'] = 10; ?>
               <tr><td>Number of posts:</td>
                 <td><input type='text' class='in2' name='cfb_com[numpost]' value='<?=$cfb_com['numpost']?>' /> (default: 10)</td>
                 </tr>
                 <tr>
                        <td>Color Scheme: </td>
                        <td>
                        <select name='cfb_com[schm]' class='in2'>
                            <option value='light' <?=selected($cfb_com['schm'], 'light')?> >Light</option>
                            <option value='dark' <?=selected($cfb_com['schm'], 'dark')?> >Dark</option>
                       </select>
                       </td>
                       </tr>
                       <tr>
                            <td>Comment Count Text: </td><td>
                            <input type='text' class='in2' name='cfb_com[txtpre]' value='<?=$cfb_com['txtpre'] ?>'>
                            344
                            <input type='text' class='in2' name='cfb_com[txtpost]' value='<?=$cfb_com['txtpost'] ?>'>
                            leave two box blank if you don't want to show comment count text
                            </td>
                         </tr>
                         <tr>
                            <td>Text Before(Title) Comment Box: </td><td>
                            <input type='text' class='in1' name='cfb_com[title]' value='<?=$cfb_com['title'] ?>'>
                            </td>
                         </tr>
                         <tr>
                            <td>Comment box css style: </td><td>
                            <input type='text' class='in1' name='cfb_com[css]' value='<?=$cfb_com['css'] ?>'>
                            </td>
                         </tr>
                         <tr>
                            <td>Comment Moderators Facebook ID</td><td>
                            <input type='text' class='in1' name='cfb_com[mods]' value='<?=$cfb_com['mods'] ?>'>
                            </td>
                         </tr>
                            
                         
                       </table><br/><br/>
                       
                       <input  type="submit" class="button-primary" value="<?php _e('Update Comment Settings'); ?>" />
    
                </form>
		</div>
	</div> <!-- postbox -->
    <?php
    /* =======================================
    //      Like button setting
    //======================================== */ ?>
        <div class='postbox'>
            <h3>Like & Share Button Settings</h3>
            <div class='fbds'>
                <form method='post' action='options.php'>
                        <?php settings_fields('cfb_likeform'); ?>
                        <?php $cfb_like = get_option('cfb_like'); ?>
                 <table>
                    <tr>
                        <td>Position of Like button: </td>
                        <td>
                        <select name='cfb_like[pos]' class='in1'>' 
                            <option value='after_title' <?=selected($cfb_like['pos'], 'after_title')?> >After Post Title</option>
                            <option value='after_content' <?=selected($cfb_like['pos'], 'after_content')?> > After Post </option>
                            <option value='after_tags' <?=selected($cfb_like['pos'], 'after_tags')?> >After Post Tags</option>
                       </select>
                       </td>
                    </tr>
                    <tr>
                    <td>Options: </td>
                        <td>
                        <select name='cfb_like[option]' class='in1'>
                            <option value='individual' <?=selected($cfb_like['option'], 'individual')?> >Individual Setting is the best</option>
                            <option value='enable_all' <?=selected($cfb_like['option'], 'enable_all')?> >Enable Like Button for all pages/posts</option>
                            <option value='disable_all' <?=selected($cfb_like['option'], 'disable_all')?> >Disable Like Button for all pages/posts</option>
                            <option value='enpage' <?=selected($cfb_like['option'], 'enpage')?> >Enable for all pages</option>
                            <option value='enpost' <?=selected($cfb_like['option'], 'enpost')?> >Enable for posts</option>
                       </select> when enabled/disabled for one type, individual setting will work for another
                       </td>
                   </tr>
                    <tr>
                        <td><label for='cfb_like[send]'>Add Send Button</label></td>
                        <td><input type='checkbox' name="cfb_like[send]" <?php checked($cfb_like['send'], 'on'); ?> /></td>
                  </tr>
                  <tr>
                    <td>Like Button layout: </td>
                        <td>
                        <select name='cfb_like[layout]' class='in2'>' 
                            <option value='standard' <?=selected($cfb_like['layout'], 'standard')?> >standard</option>
                            <option value='button_count' <?=selected($cfb_like['layout'], 'button_count')?> > button_count</option>
                            <option value='box_count' <?=selected($cfb_like['layout'], 'box_count')?> >box_count</option>
                       </select>
                       </td>
                    </tr>
                    <tr>
                         <td>Width: </td><td>
                            <input type='text' class='in1' name='cfb_like[width]' value='<?=$cfb_like['width'] ?>'>
                            </td>
                    </tr>
                    <tr>
                        <td><label for='cfb_like[faces]'>Show Faces</label></td>
                        <td><input type='checkbox' name="cfb_like[faces]" <?php checked($cfb_like['faces'], 'on'); ?> /></td>
                  </tr>
                  <tr>
                    <td>Verb to display: </td>
                        <td>
                        <select name='cfb_like[verb]' class='in2'>' 
                            <option value='like' <?=selected($cfb_like['verb'], 'like')?> >like</option>
                            <option value='recommend' <?=selected($cfb_like['verb'], 'recommend')?> >recommend</option>
                       </select>
                       </td>
                    </tr>
                    <tr>
                    <td>Color scheme: </td>
                        <td>
                        <select name='cfb_like[schm]' class='in2'>' 
                            <option value='light' <?=selected($cfb_like['schm'], 'light')?> >Light</option>
                            <option value='dark' <?=selected($cfb_like['schm'], 'dark')?> >Dark</option>
                       </select>
                       </td>
                    </tr>
                    <tr>
                    <td>Font: </td>
                        <td>
                        <select name='cfb_like[font]' class='in2'>' 
                            <option value='arial' <?=selected($cfb_like['font'], 'arial')?> >arial</option>
                            <option value='lucida grande' <?=selected($cfb_like['font'], 'lucida grande')?> >lucida grande</option>
                            <option value='segoe ui' <?=selected($cfb_like['font'], 'segoe ui')?> >segoe ui</option>
                            <option value='tahoma' <?=selected($cfb_like['font'], 'tahoma')?> >tahoma</option>
                            <option value='trebuchet ms' <?=selected($cfb_like['font'], 'trebuchet ms')?> >trebuchet ms</option>
                            <option value='verdana' <?=selected($cfb_like['font'], 'verdana')?> ></option>
                       </select>
                       </td>
                    </tr>
                    <tr>
                         <td>Like button container div style: </td><td>
                            <input type='text' class='in1' name='cfb_like[css]' value='<?=$cfb_like['css'] ?>'>
                            </td>
                    </tr>
			<tr><td><h4>Share Button Settings</h4></td></tr>
		<tr>
                        <td><label for='cfb_like[share_off]'>Exclude Share Button with share count</label></td>
                        <td><input type='checkbox' name="cfb_like[share_off]" <?php checked($cfb_like['share_off'], 'on'); ?> /></td>
                  </tr>
		
                    </table>
                    <br/><br/>
                <input  type="submit" class="button-primary" value="<?php _e('Update Like Settings'); ?>" />
    
                </form>
                 </div>
              </div> <!--postbox-->
	<?php
    /* =======================================
    //      Notification settings
    //======================================== */ 
	?>
        <div class='postbox'>
            <h3>Notifications</h3>
            <div class='fbds'>
            <form method='post' action='options.php'>
                        <?php settings_fields('cfb_notform'); ?>
                        <?php $cfb_not = get_option('cfb_not'); ?>
                 <table>
                    <tr>
                        <td><label for='cfb_not[ec]'>Enable Comment Notification</label></td>
                        <td><input type='checkbox' name="cfb_not[ec]" <?php checked($cfb_not['ec'], 'on'); ?> /></td>
                  	</tr>
                  	<tr>
                        <td><label for='cfb_not[el]'>Enable Like Notification</label></td>
                        <td><input type='checkbox' name="cfb_not[el]" <?php checked($cfb_not['el'], 'on'); ?> /></td>
                  	</tr>
                  	<tr>
                            <td>Send notification to: </td><td>
                            <input type='text' class='in1' name='cfb_not[to]' value='<?=$cfb_not['to'] ?>'> 
                            </td>
                    </tr>
                    <tr>
                            <td>Send notification from: </td><td>
                            <input type='text' class='in1' name='cfb_not[from]' value='<?=$cfb_not['from'] ?>'>
                            </td>
                    </tr>
             </table><br/><br/>
                       
                       <input  type="submit" class="button-primary" value="<?php _e('Update Notification Settings'); ?>" />
    
                </form>
             
            </div>
            
		</div>
		
		<?php
    /* =======================================
    //     Opengraph settings
    //======================================== */ 
	?>
        <div class='postbox'>
            <h3>Opengraph Settings:</h3>
            <div class='fbds'>
            <form method='post' action='options.php'>
                        <?php settings_fields('cfb_ogform'); ?>
                        <?php $cfb_og = get_option('cfb_og'); ?>
                 <table>
                 	<tr>
                        <td><label for='cfb_og[eog]'>Enable OpenGraph</label></td>
                        <td><input type='checkbox' name="cfb_og[eog]" <?php checked($cfb_og['eog'], 'on'); ?> /></td>
                  	</tr>
                  	<tr>
                            <td>Thumbnail: </td><td>
                            <input type='text' class='in1' name='cfb_og[thumb]' value='<?=$cfb_og['thumb'] ?>'> 
                            </td>
                    </tr>
                    <tr>
                            <td>Blog Language: </td><td>
							<select name="cfb_og[lang]" value="<?=$cfb_og['lang'] ?>">
												<option value="<?=$cfb_og['lang'] ?>" selected="selected" ><?=$cfb_og['lang'] ?></option>
												<option value="af_ZA">Afrikaans</option>
												<option value="ar_AR">Arabic</option>
												<option value="az_AZ">Azeri</option>
												<option value="be_BY">Belarusian</option>
												<option value="bg_BG">Bulgarian</option>
												<option value="bn_IN">Bengali</option>
												<option value="bs_BA">Bosnian</option>
												<option value="ca_ES">Catalan</option>
												<option value="cs_CZ">Czech</option>
												<option value="cy_GB">Welsh</option>
												<option value="da_DK">Danish</option>
												<option value="de_DE">German</option>
												<option value="el_GR">Greek</option>
												<option value="en_GB">English (UK)</option>
												<option value="en_PI">English (Pirate)</option>
												<option value="en_UD">English (Upside Down)</option>
												<option value="en_US">English (US)</option>
												<option value="eo_EO">Esperanto</option>
												<option value="es_ES">Spanish (Spain)</option>
												<option value="es_LA">Spanish</option>
												<option value="et_EE">Estonian</option>
												<option value="eu_ES">Basque</option>
												<option value="fa_IR">Persian</option>
												<option value="fb_LT">Leet Speak</option>
												<option value="fi_FI">Finnish</option>
												<option value="fo_FO">Faroese</option>
												<option value="fr_CA">French (Canada)</option>
												<option value="fr_FR">French (France)</option>
												<option value="fy_NL">Frisian</option>
												<option value="ga_IE">Irish</option>
												<option value="gl_ES">Galician</option>
												<option value="he_IL">Hebrew</option>
												<option value="hi_IN">Hindi</option>
												<option value="hr_HR">Croatian</option>
												<option value="hu_HU">Hungarian</option>
												<option value="hy_AM">Armenian</option>
												<option value="id_ID">Indonesian</option>
												<option value="is_IS">Icelandic</option>
												<option value="it_IT">Italian</option>
												<option value="ja_JP">Japanese</option>
												<option value="ka_GE">Georgian</option>
												<option value="km_KH">Khmer</option>
												<option value="ko_KR">Korean</option>
												<option value="ku_TR">Kurdish</option>
												<option value="la_VA">Latin</option>
												<option value="lt_LT">Lithuanian</option>
												<option value="lv_LV">Latvian</option>
												<option value="mk_MK">Macedonian</option>
												<option value="ml_IN">Malayalam</option>
												<option value="ms_MY">Malay</option>
												<option value="nb_NO">Norwegian (bokmal)</option>
												<option value="ne_NP">Nepali</option>
												<option value="nl_NL">Dutch</option>
												<option value="nn_NO">Norwegian (nynorsk)</option>
												<option value="pa_IN">Punjabi</option>
												<option value="pl_PL">Polish</option>
												<option value="ps_AF">Pashto</option>
												<option value="pt_BR">Portuguese (Brazil)</option>
												<option value="pt_PT">Portuguese (Portugal)</option>
												<option value="ro_RO">Romanian</option>
												<option value="ru_RU">Russian</option>
												<option value="sk_SK">Slovak</option>
												<option value="sl_SI">Slovenian</option>
												<option value="sq_AL">Albanian</option>
												<option value="sr_RS">Serbian</option>
												<option value="sv_SE">Swedish</option>
												<option value="sw_KE">Swahili</option>
												<option value="ta_IN">Tamil</option>
												<option value="te_IN">Telugu</option>
												<option value="th_TH">Thai</option>
												<option value="tl_PH">Filipino</option>
												<option value="tr_TR">Turkish</option>
												<option value="uk_UA">Ukrainian</option>
												<option value="vi_VN">Vietnamese</option>
												<option value="zh_CN">Simplified Chinese (China)</option>
												<option value="zh_HK">Traditional Chinese (Hong Kong)</option>
												<option value="zh_TW">Traditional Chinese (Taiwan)</option>
											</select>                            
                            </td>
                    </tr>
             </table><br/><br/>
             
                       
                       <input  type="submit" class="button-primary" value="<?php _e('Update OpenGraph Settings'); ?>" />
    
                </form>
             
            </div>
            
		</div>              
                            
		<div class='postbox'>
            <div class='copr fbds'>
      <br/><br/>
            
                Developer: Matheus Bratfisch<br/>
                Email: matheusbrat@gmail.com<br/>
                Web: <a href="http://www.matbra.com" target="_blank">http://www.matbra.com</a><br/>
                Based on <a href="http://wordpress.org/extend/plugins/total-facebook/" target="_blank">Total Facebook</a>
                <br/>
                <br/><br/>
<?php 
//========================
//= Donation Link
//========================= 
?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

             </div>
       </div>
                 
        

</div> <!-- wrap -->
 <?php } ?>