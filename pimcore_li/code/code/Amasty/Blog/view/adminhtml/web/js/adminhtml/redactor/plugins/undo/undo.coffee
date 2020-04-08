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
RedactorPlugins.undo = ()->

  init: ()->

    undo = this.button.addFirst('undo', 'Undo')
    redo = this.button.addAfter('undo', 'redo', 'Redo')

    @button.addCallback(undo, ()->
      @buffer.undo()
      @code.sync()


      return
    );
    @button.addCallback(redo, ()->
      @buffer.redo()
      @code.sync()

      return
    );

    return



