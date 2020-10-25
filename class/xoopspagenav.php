<?php

// $Id: xoopspagenav.php,v 1.1 2003/10/15 05:36:27 mikhail Exp $
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <https://www.xoops.org>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
// Author of File: 							     //
// Kazumi Ono (http://www.myweb.ne.jp/, https://www.xoops.org/)               //
// ------------------------------------------------------------------------- //
class XoopsPageNav
{
    public $total;

    public $perpage;

    public $current;

    public $url;

    public function __construct($total_items, $items_perpage, $current_start, $start_name = 'start', $extra_arg = '')
    {
        $this->total = (int)$total_items;

        $this->perpage = (int)$items_perpage;

        $this->current = (int)$current_start;

        if ('' != $extra_arg && ('&amp;' != mb_substr($extra_arg, -5) || '&' != mb_substr($extra_arg, -1))) {
            $extra_arg .= '&amp;';
        }

        $this->url = $GLOBALS['PHP_SELF'] . '?' . $extra_arg . trim($start_name) . '=';
    }

    public function renderNav($offset = 4)
    {
        if ($this->total < $this->perpage) {
            return;
        }

        $total_pages = ceil($this->total / $this->perpage);

        if ($total_pages > 1) {
            $ret = '';

            $prev = $this->current - $this->perpage;

            if ($prev >= 0) {
                $ret .= '<a href="' . $this->url . $prev . '" title="' . _PREC . '"><img src="images/gauche.gif" width="11" height="11" border="0" alt="' . _PREC . '"></a>&nbsp; ';
            }

            $counter = 1;

            $current_page = (int)floor(($this->current + $this->perpage) / $this->perpage);

            while ($counter <= $total_pages) {
                if ($counter == $current_page) {
                    $ret .= '<b>(' . $counter . ')</b> ';
                } elseif (($counter > $current_page - $offset && $counter < $current_page + $offset) || 1 == $counter || $counter == $total_pages) {
                    if ($counter == $total_pages && $current_page < $total_pages - $offset) {
                        $ret .= '... ';
                    }

                    $ret .= '<a href="' . $this->url . (($counter - 1) * $this->perpage) . '">' . $counter . '</a> ';

                    if (1 == $counter && $current_page > 1 + $offset) {
                        $ret .= '... ';
                    }
                }

                $counter++;
            }

            $next = $this->current + $this->perpage;

            if ($this->total > $next) {
                $ret .= '&nbsp;<a href="' . $this->url . $next . '" title="' . _SUIV . '"><img src="images/droite.gif" width="11" height="11" border="0" alt="' . _SUIV . '"></a> ';
            }
        }

        return $ret;
    }

    public function renderSelect($showbutton = false)
    {
        if ($this->total < $this->perpage) {
            return;
        }

        $total_pages = ceil($this->total / $this->perpage);

        $ret = '';

        if ($total_pages > 1) {
            $ret = '<form name="pagenavform">';

            $ret .= '<select name="pagenavselect" onchange="location=this.options[this.options.selectedIndex].value;">';

            $counter = 1;

            $current_page = (int)floor(($this->current + $this->perpage) / $this->perpage);

            while ($counter <= $total_pages) {
                if ($counter == $current_page) {
                    $ret .= '<option value="' . $this->url . (($counter - 1) * $this->perpage) . '" selected="selected">' . $counter . '</option>';
                } else {
                    $ret .= '<option value="' . $this->url . (($counter - 1) * $this->perpage) . '">' . $counter . '</option>';
                }

                $counter++;
            }

            $ret .= '</select>';

            if ($showbutton) {
                $ret .= '&nbsp;<input type="submit" vallue="' . _GO . '">';
            }

            $ret .= '</form>';
        }

        return $ret;
    }
}
