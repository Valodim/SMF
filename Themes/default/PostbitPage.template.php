<?php
function template_postbit_normal(&$message, $ignoring)
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $topic;
	
	// Show the message anchor and a "new" anchor if this message is new.
	$cclass = $message['approved'] ? ($message['alternate'] == 0 ? 'windowbg ' : 'windowbg2 ') : 'approvebg ';
	echo '
	<div class="',$cclass,'flat_container" style="padding:0;" data-mid="',$message['id'], '">';

	if ($message['id'] != $context['first_message'])
		echo '
	<a id="msg', $message['id'], '"></a>', $message['first_new'] ? '<a id="new"></a>' : '';
	
	// Show information about the poster of this message.
	echo '
	<div class="floatleft " style="max-width:65px;">
	<ul class="reset smalltext" id="msg_', $message['id'], '_extra_info">';
	// Done with the information about the poster... on to the post itself.
	if(!empty($message['member']['avatar']['image']))
		echo '
		<li class="medium_avatar">
		<a href="', $scripturl, '?action=profile;u=', $message['member']['id'], '">
		', $message['member']['avatar']['image'], '
		</a>
		</li>';
	else
		echo '
		<li class="medium_avatar">
			<a href="', $scripturl, '?action=profile;u=', $message['member']['id'], '">
			<img src="',$settings['images_url'],'/unknown.png" alt="avatar" />
			</a>
		</li>';
	echo '
	
		</ul>
		</div>
		<div class="postarea" style="margin-left:60px;">
			<div>
			<div class="windowbg3 smalltext smallpadding" style="margin-left:-60px;border:0;">
			<h5 style="display:inline;" id="subject_', $message['id'], '">
			', $message['subject'], '
			</h5>
			<br>',
			$txt['by'], ' ', $message['member']['link'],'
			<span class="',($message['new'] ? 'permalink_new' : 'permalink_old'),'"><a href="', $message['href'], '" rel="nofollow">',$message['permalink'],'</a></span>
			<span class="smalltext">&nbsp;',$message['time'], '</span>
			<div id="msg_', $message['id'], '_quick_mod"></div>';

		echo '
		</div><div class="clear_right"></div></div>';

	// Ignoring this user? Hide the post.
	if ($ignoring)
		echo '
		<div id="msg_', $message['id'], '_ignored_prompt">
			', $txt['ignoring_user'], '
			<a href="#" id="msg_', $message['id'], '_ignored_link" style="display: none;">', $txt['show_ignore_user_post'], '</a>
		</div>';

	// Show the post itself, finally!
	echo '
		<div class="post" id="msg_', $message['id'], '">';

	if (!$message['approved'] && $message['member']['id'] != 0 && $message['member']['id'] == $context['user']['id'])
		echo '
		<div class="approve_post">
			', $txt['post_awaiting_approval'], '
		</div>';
	echo '
		<article>
			', $message['body'],'
		</article>
		</div>';
						
	// Assuming there are attachments...
	if (!empty($message['attachment']))
	{
		echo '
		<div id="msg_', $message['id'], '_footer" class="attachments smalltext">
		<ol class="post_attachments">';

		$last_approved_state = 1;
		foreach ($message['attachment'] as $attachment)
		{
			echo '
			<li>';
			// Show a special box for unapproved attachments...
			if ($attachment['is_approved'] != $last_approved_state)
			{
				$last_approved_state = 0;
				echo '
			<fieldset>
				<legend>', $txt['attach_awaiting_approve'];

				if ($context['can_approve'])
					echo '
					&nbsp;[<a href="', $scripturl, '?action=attachapprove;sa=all;mid=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve_all'], '</a>]';

				echo '
				</legend>';
			}

			if ($attachment['is_image'])
			{
				if ($attachment['thumbnail']['has_thumb'])
					//echo '<a href="', $attachment['href'], ';image" id="link_', $attachment['id'], '" onclick="', $attachment['thumbnail']['javascript'], '"><img src="', $attachment['thumbnail']['href'], '" alt="" id="thumb_', $attachment['id'], '" /></a><br />';
							echo '<a rel="prettyPhoto[gallery]" href="', $attachment['href'], ';image" id="link_', $attachment['id'], '" class="attach_thumb"><img src="', $attachment['thumbnail']['href'], '" alt="" id="thumb_', $attachment['id'], '" /></a>';
				else
					echo '
				<img src="' . $attachment['href'] . ';image" alt="" width="' . $attachment['width'] . '" height="' . $attachment['height'] . '"/>';
			}
			echo '
				<a href="' . $attachment['href'] . '">' . $attachment['name'] . '</a><br />';

			if (!$attachment['is_approved'] && $context['can_approve'])
				echo '
				[<a href="', $scripturl, '?action=attachapprove;sa=approve;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve'], '</a>]&nbsp;|&nbsp;[<a href="', $scripturl, '?action=attachapprove;sa=reject;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['delete'], '</a>] ';
			echo '
									', $attachment['size'], ($attachment['is_image'] ? ', ' . $attachment['real_width'] . 'x' . $attachment['real_height'] . '<br />' . $txt['attach_viewed'] : '<br />' . $txt['attach_downloaded']) . ' ' . $attachment['downloads'] . ' ' . $txt['attach_times'] . '.<br />
									
				</li>
									';
		}

		// If we had unapproved attachments clean up.
		if ($last_approved_state == 0)
			echo '
			</fieldset>';

		echo '
			</ol>
			</div>';
	}

	echo '
		</div>
		<div class="moderatorbar">';
				
	echo '
		</div>';
					if($message['likes_count'] > 0 || !empty($message['likelink'])) 
						echo '
		<div class="likebar">
		<div class="floatright">',$message['likelink'],'</div>
		<span id="likers_msg_',$message['id'],'">',$message['likers'],'</span>
		<div class="clear"></div></div>';
					
					echo '
		<div class="post_bottom" style="background:transparent;">
		<div style="display:inline;">';
						echo '
		<span id="modified_', $message['id'], '">';
						if ($settings['show_modify'] && !empty($message['modified']['name']))
							echo '
		<em>', $txt['last_edit'], ': ', $message['modified']['time'], ' ', $txt['by'], ' ', $message['modified']['name'], '</em>';

						echo '
		</span>
		</div>
		<div class="reportlinks">
		<ul class="floatright reset quickbuttons_linkstyle" style="line-height:100%;">';

	// Maybe we can approve it, maybe we should?
	if ($message['can_approve'])
		echo '
			<li class="approve_button"><a href="', $scripturl, '?action=moderate;area=postmod;sa=approve;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve'], '</a></li>';

	// Can they reply? Have they turned on quick reply?
	if ($context['can_quote'] && !empty($options['display_quick_reply']))
		echo '
			<li class="quote_button"><a href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';last_msg=', $context['topic_last_message'], '" onclick="return oQuickReply.quote(', $message['id'], ');">', $txt['quote'], '</a></li>
			<li class="quote_button" id="mquote_' . $message['id'] . '"><a href="#!" onclick="return mquote(' . $message['id'] . ',\'none\');">', $txt['add_mq'], '</a></li>
			<li class="quote_button" style="display:none;" id="mquote_remove_' . $message['id'] . '"><a href="#!" onclick="return mquote(' . $message['id'] . ',\'remove\');">', $txt['remove_mq'], '</a></li>';

	// So... quick reply is off, but they *can* reply?
	elseif ($context['can_quote'])
		echo '
			<li class="quote_button"><a href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';last_msg=', $context['topic_last_message'], '">', $txt['quote'], '</a></li>
			<li class="quote_button" id="mquote_' . $message['id'] . '"><a href="#!" onclick="return mquote(' . $message['id'] . ',\'none\');">', $txt['add_mq'], '</a></li>
			<li class="quote_button" style="display:none;" id="mquote_remove_' . $message['id'] . '"><a href="#!" onclick="return mquote(' . $message['id'] . ',\'remove\');">', $txt['remove_mq'], '</a></li>';

	// Can the user modify the contents of this post?
	if ($message['can_modify'])
		echo '
			<li class="modify_button"><a onclick="oQuickModify.modifyMsg(\'', $message['id'], '\');return(false);" href="', $scripturl, '?action=post;msg=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], '">', $txt['modify'], '</a></li>';

	// How about... even... remove it entirely?!
	if ($message['can_remove'])
		echo '
			<li class="remove_button"><a href="', $scripturl, '?action=deletemsg;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['remove_message'], '?\');">', $txt['remove'], '</a></li>';

	// What about splitting it off the rest of the topic?
	if ($context['can_split'] && !empty($context['real_num_replies']))
		echo '
			<li class="split_button"><a href="', $scripturl, '?action=splittopics;topic=', $context['current_topic'], '.0;at=', $message['id'], '">', $txt['split'], '</a></li>';

	// Can we restore topics?
	if ($context['can_restore_msg'])
		echo '
			<li class="restore_button"><a href="', $scripturl, '?action=restoretopic;msgs=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['restore_message'], '</a></li>';

	// Show a checkbox for quick moderation?
	if (!empty($options['display_quick_mod']) && $message['can_remove'])
		echo '
			<li class="inline_mod_check" style="display: none;" id="in_topic_mod_check_', $message['id'], '"></li>';

	echo '
		</ul>';
						

	// Maybe they want to report this post to the moderator(s)?
	if ($context['can_report_moderator'])
		echo '
		<a href="', $scripturl, '?action=reporttm;topic=', $context['current_topic'], '.', $message['counter'], ';msg=', $message['id'], '">', $txt['report'], '</a>';

	// Can we issue a warning because of this post?  Remember, we can't give guests warnings.
	if ($context['can_issue_warning'] && !$message['is_message_author'] && !$message['member']['is_guest'])
		echo '
		&nbsp;&nbsp;&nbsp;<a href="', $scripturl, '?action=profile;area=issuewarning;u=', $message['member']['id'], ';msg=', $message['id'], '">', $txt['issue_warning'], '</a>';

	// Show the IP to this user for this post - because you can moderate?
	if ($context['can_moderate_forum'] && !empty($message['member']['ip']))
		echo '
		&nbsp;&nbsp;&nbsp;IP: <a href="', $scripturl, '?action=', !empty($message['member']['is_guest']) ? 'trackip' : 'profile;area=tracking;sa=ip;u=' . $message['member']['id'], ';searchip=', $message['member']['ip'], '">', $message['member']['ip'], '</a> <a href="', $scripturl, '?action=helpadmin;help=see_admin_ip" onclick="return reqWin(this.href);" class="help">(?)</a>';
	// Or, should we show it because this is you?
	elseif ($message['can_see_ip'])
		echo '
		&nbsp;&nbsp;&nbsp;IP: <a href="', $scripturl, '?action=helpadmin;help=see_member_ip" onclick="return reqWin(this.href);" class="help">', $message['member']['ip'], '</a>';
	// Okay, are you at least logged in?  Then we can show something about why IPs are logged...
	//elseif (!$context['user']['is_guest'])
	//	echo '
	//						<a href="', $scripturl, '?action=helpadmin;help=see_member_ip" onclick="return reqWin(this.href);" class="help">', $txt['logged'], '</a>';
	echo '
		</div><div class="clear"></div></div></div>';
}

function template_postbit_blog(&$message, $ignoring)
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings, $topic;
	
	// Show the message anchor and a "new" anchor if this message is new.
	$cclass = $message['approved'] ? ($message['alternate'] == 0 ? 'windowbg ' : 'windowbg2 ') : 'approvebg ';
	echo '
		<div data-mid="',$message['id'], '">';

	if ($message['id'] != $context['first_message'])
		echo '
			<a id="msg', $message['id'], '"></a>', $message['first_new'] ? '<a id="new"></a>' : '';
	
	// Show information about the poster of this message.
	echo '
		<div>
		  <div class="smalltext righttext">
		  Posted by: ',$message['member']['link'],',&nbsp;
		  <span class="smalltext">',$message['time'], '</span>						  
		  </div>
		  <span style="display:none;" id="subject_', $message['id'], '">
		 ', $message['subject'], '
		  </span>	  
		</div>
		<div id="msg_', $message['id'], '_quick_mod"></div>';

	// Ignoring this user? Hide the post.
	if ($ignoring)
		echo '
				<div id="msg_', $message['id'], '_ignored_prompt">
					', $txt['ignoring_user'], '
					<a href="#" id="msg_', $message['id'], '_ignored_link" style="display: none;">', $txt['show_ignore_user_post'], '</a>
				</div>';

	// Show the post itself, finally!
	echo '
						<div class="post clear_left" style="margin:0;padding:0;" id="msg_', $message['id'], '">';

	if (!$message['approved'] && $message['member']['id'] != 0 && $message['member']['id'] == $context['user']['id'])
		echo '
							<div class="approve_post">
								', $txt['post_awaiting_approval'], '
							</div>';
	echo '
							<article>
							', $message['body'], '
							</article>
						</div>';

	// Assuming there are attachments...
	if (!empty($message['attachment']))
	{
		echo '
						<div id="msg_', $message['id'], '_footer" class="attachments smalltext">
							<ol class="post_attachments">';

		$last_approved_state = 1;
		foreach ($message['attachment'] as $attachment)
		{
			echo '<li>';
			// Show a special box for unapproved attachments...
			if ($attachment['is_approved'] != $last_approved_state)
			{
				$last_approved_state = 0;
				echo '
								<fieldset>
									<legend>', $txt['attach_awaiting_approve'];

				if ($context['can_approve'])
					echo '&nbsp;[<a href="', $scripturl, '?action=attachapprove;sa=all;mid=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve_all'], '</a>]';

				echo '</legend>';
			}

			if ($attachment['is_image'])
			{
				if ($attachment['thumbnail']['has_thumb'])
					//echo '<a href="', $attachment['href'], ';image" id="link_', $attachment['id'], '" onclick="', $attachment['thumbnail']['javascript'], '"><img src="', $attachment['thumbnail']['href'], '" alt="" id="thumb_', $attachment['id'], '" /></a><br />';
							echo '<a rel="prettyPhoto[gallery]" href="', $attachment['href'], ';image" id="link_', $attachment['id'], '" class="attach_thumb"><img src="', $attachment['thumbnail']['href'], '" alt="" id="thumb_', $attachment['id'], '" /></a>';
				else
					echo '
									<img src="' . $attachment['href'] . ';image" alt="" width="' . $attachment['width'] . '" height="' . $attachment['height'] . '"/>';
			}
			echo '
									<a href="' . $attachment['href'] . '">' . $attachment['name'] . '</a><br />';

			if (!$attachment['is_approved'] && $context['can_approve'])
				echo '
									[<a href="', $scripturl, '?action=attachapprove;sa=approve;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve'], '</a>]&nbsp;|&nbsp;[<a href="', $scripturl, '?action=attachapprove;sa=reject;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['delete'], '</a>] ';
			echo '
									', $attachment['size'], ($attachment['is_image'] ? ', ' . $attachment['real_width'] . 'x' . $attachment['real_height'] . '<br />' . $txt['attach_viewed'] : '<br />' . $txt['attach_downloaded']) . ' ' . $attachment['downloads'] . ' ' . $txt['attach_times'] . '.<br />
									
									</li>
									';
		}

		// If we had unapproved attachments clean up.
		if ($last_approved_state == 0)
			echo '
								</fieldset>';

		echo '
							</ol>
						</div>';
	}

	echo '
					<div class="moderatorbar" style="margin-left:10px;">';
	echo '
		</div>';
	if($message['likes_count'] > 0 || !empty($message['likelink'])) 
		echo '<div class="likebar blue_container">
			<div class="floatright">',$message['likelink'],'</div>
			<span id="likers_msg_',$message['id'],'">',$message['likers'],'</span>
			<div class="clear"></div></div>';
					
	echo '<div class="post_bottom" style="background-color:transparent;">
			<div style="display:inline;">';
			// Show online and offline buttons?

			echo '<span class="modified" id="modified_', $message['id'], '">';
			// Show "� Last Edit: Time by Person �" if this post was edited.
			if ($settings['show_modify'] && !empty($message['modified']['name']))
				echo '
					<em>', $txt['last_edit'], ': ', $message['modified']['time'], ' ', $txt['by'], ' ', $message['modified']['name'], '</em>';

				echo '
				</span>';
			echo '</div>
				<div class="reportlinks">
				<ul class="floatright reset smalltext quickbuttons">';

	// Maybe we can approve it, maybe we should?
	if ($message['can_approve'])
		echo '
								<li class="approve_button"><a href="', $scripturl, '?action=moderate;area=postmod;sa=approve;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve'], '</a></li>';

	// Can the user modify the contents of this post?
	if ($message['can_modify'])
		echo '
			<li class="modify_button"><a onclick="oQuickModify.modifyMsg(\'', $message['id'], '\');return(false);" href="', $scripturl, '?action=post;msg=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], '">', $txt['modify'], '</a></li>';

	// How about... even... remove it entirely?!
	if ($message['can_remove'])
		echo '
								<li class="remove_button"><a href="', $scripturl, '?action=deletemsg;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['remove_message'], '?\');">', $txt['remove'], '</a></li>';

	// What about splitting it off the rest of the topic?
	if ($context['can_split'] && !empty($context['real_num_replies']))
		echo '
								<li class="split_button"><a href="', $scripturl, '?action=splittopics;topic=', $context['current_topic'], '.0;at=', $message['id'], '">', $txt['split'], '</a></li>';

	// Can we restore topics?
	if ($context['can_restore_msg'])
		echo '
								<li class="restore_button"><a href="', $scripturl, '?action=restoretopic;msgs=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['restore_message'], '</a></li>';

	// Show a checkbox for quick moderation?
	if (!empty($options['display_quick_mod']) && $message['can_remove'])
		echo '
								<li class="inline_mod_check" style="display: none;" id="in_topic_mod_check_', $message['id'], '"></li>';

		echo '
						</ul>
						
						';

	echo '
						</div><div class="clear"></div></div>';


	echo "</div>";
}
?>