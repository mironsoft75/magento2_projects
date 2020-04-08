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
RedactorPlugins.magento = ()->
  values: {}
  init: ()->
    button = @button.add('magento', magentoVariablesLabel)
    dropdown = {}
    i = 0
    for row in magentoVariables
      i++
      this.magento.values["item#{i}"] = row.value
      dropdown["item#{i}"] =
        title: row.label
        func: @magento.fire

    @button.addDropdown(button, dropdown)
    return

  fire: (id)->
    this.insert.text(this.magento.values[id]);
    return






