{*
 * @name      EosAlpha BBS
 * @copyright 2011 Alex Vie silvercircle(AT)gmail(DOT)com
 *
 * This software is a derived product, based on:
 *
 * Simple Machines Forum (SMF)
 * copyright: 2011 Simple Machines (http://www.simplemachines.org)
 * license:   BSD, See included LICENSE.TXT for terms and conditions.
 *
 * @version 1.0pre
 *}
<!DOCTYPE html >
<html id="_S_" lang="en-US">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="robots" content="noindex" />
    <link rel="canonical" href="{$C.canonical_url}" />
    <title>{$T.print_page} - {$C.topic_subject}</title>
    <style type="text/css">
      body, a
      {
        color: #000;
        background: #fff;
      }
      body, td, .normaltext
      {
        font-family: Verdana, arial, helvetica, serif;
        font-size: small;
      }
      h1#title
      {
        font-size: large;
        font-weight: bold;
      }
      h2#linktree
      {
        margin: 1em 0 2.5em 0;
        font-size: small;
        font-weight: bold;
      }
      dl#posts
      {
        width: 90%;
        margin: 0;
        padding: 0;
        list-style: none;
      }
      dt.postheader
      {
        border: solid #000;
        border-width: 1px 0;
        padding: 4px 0;
      }
      dd.postbody
      {
        margin: 1em 0 2em 2em;
      }
      table
      {
        empty-cells: show;
      }
      blockquote, code
      {
        border: 1px solid #000;
        margin: 3px;
        padding: 1px;
        display: block;
      }
      code
      {
        font: x-small monospace;
      }
      blockquote
      {
        font-size: x-small;
      }
      .smalltext, .quoteheader, .codeheader
      {
        font-size: x-small;
      }
      .largetext
      {
        font-size: large;
      }
      .centertext
      {
        text-align: center;
      }
      hr
      {
        height: 1px;
        border: 0;
        color: black;
        background-color: black;
      }
    </style>
  </head>
  <body>
    <h1 id="title">{$C.forum_name_html_safe}</h1>
    <h2 id="linktree">{$C.category_name} => {(!empty($C.parent_boards)) ? (" => "|implode:$C.parent_boards|cat:' => ') : ''}{$C.board_name} => {$T.topic_started}: {$C.poster_name} {$T.search_on} {$C.post_time}</h2>
    <dl id="posts">
    {foreach $C.posts as $post}
      <dt class="postheader">
        {$T.title}: <strong>{$post.subject}</strong><br />
        {$T.post_by}: <strong>{$post.member}</strong> {$T.search_on} <strong>{$post.time}</strong>
      </dt>
      <dd class="postbody">
        {$post.body}
      </dd>
    {/foreach}
    </dl>
    <div id="footer" class="smalltext">
    </div>
  </body
</html>
