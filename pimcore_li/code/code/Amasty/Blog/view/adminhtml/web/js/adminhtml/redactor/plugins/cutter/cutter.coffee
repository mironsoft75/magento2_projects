###
 * MagPleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE.txt
 *
 * @category   Magpleasure
 * @package    Magpleasure_Blog
 * @copyright  Copyright (c) 2012-2015 MagPleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE.txt
###

# Define plugins if have no one
RedactorPlugins.cutter = ()->
  init: ()->
    if @opts.cutter
      button = @button.add('cutter', mpBlogCutLabel)
      @button.addCallback(button, this.cutter.fire);

    @button.remove("horizontalrule")

    return

  fire: (id)->

    this.utils.saveScroll()
    this.buffer.set()

    cutters = this.$editor.find("hr.cutter")
    cutters.remove()

    hr = document.createElement('hr')
    jQuery(hr).addClass("cutter")
    this.insert.node(hr)

    this.utils.restoreScroll()
    this.code.sync()

    return







