import { Fragment } from 'wp.element';
import { __ } from 'wp.i18n';
import { InnerBlocks, __experimentalBlock as Block } from 'wp.blockEditor';
import { InspectorControls } from 'wp.blockEditor';
import { PanelBody, SelectControl } from 'wp.components';

import useUniqueId from '../../../hooks/useUniqueId';
import getBlockId from '../../../util/getBlockId';
import { getBorderCSSValue } from '../../../components/controls/border-controls/helpers';
import { getBoxShadowCSSValue } from '../../../components/controls/box-shadow-controls/helpers';
import MarginControls from '../../../components/controls/margin-controls';
import ResponsiveControl from '../../../components/controls/responsive-control/ResponsiveControl';
import BoxShadowControls from '../../../components/controls/box-shadow-controls';
import BorderControls from '../../../components/controls/border-controls';
import PopoverColorControl from '../../../components/controls/advanced-color-control/PopoverColorControl';
import FoodMenuItemEditStyle from './style';

const FoodMenuItemEdit = ({
  attributes,
  setAttributes,
  className,
  clientId,
}) => {
  const { uniqueId, backgroundColor, verticalContentAlignment } = attributes;

  useUniqueId({ attributes, setAttributes, clientId });
  const blockId = getBlockId(uniqueId);

  return (
    <Fragment>
      <div
        id={blockId}
        style={{
          backgroundColor: backgroundColor ? backgroundColor : undefined,
          ...getBorderCSSValue({ attributes }),
          ...getBoxShadowCSSValue({ attributes }),
        }}
        className={className}
      >
        <InnerBlocks
          template={[
            [
              'gutenbee/image',
              {
                caption: '',
                width: {
                  desktop: 180,
                  tablet: '',
                  mobile: '',
                },
              },
            ],
            ['gutenbee/food-menu-wrapper', {}],
          ]}
          templateLock="all"
          __experimentalTagName={Block.div}
        />
        <FoodMenuItemEditStyle attributes={attributes} />
      </div>

      <InspectorControls>
        <PanelBody title={__('Block Appearance')} initialOpen>
          <ResponsiveControl>
            {breakpoint => (
              <SelectControl
                label={__('Vertical Content Alignment')}
                value={verticalContentAlignment[breakpoint]}
                options={[
                  { value: '', label: __('Inherit') },
                  { value: 'flex-start', label: __('Top') },
                  { value: 'center', label: __('Middle') },
                  { value: 'flex-end', label: __('Bottom') },
                ]}
                onChange={value =>
                  setAttributes({
                    verticalContentAlignment: {
                      ...verticalContentAlignment,
                      [breakpoint]: value,
                    },
                  })
                }
              />
            )}
          </ResponsiveControl>

          <PopoverColorControl
            value={backgroundColor}
            defaultValue={backgroundColor || ''}
            label={__('Background Color')}
            onChange={value => {
              setAttributes({ backgroundColor: value });
            }}
          />

          <BorderControls
            attributes={attributes}
            setAttributes={setAttributes}
          />

          <BoxShadowControls
            attributes={attributes}
            setAttributes={setAttributes}
          />

          <ResponsiveControl>
            {breakpoint => (
              <MarginControls
                label={__('Padding (px)')}
                attributeKey="blockPadding"
                attributes={attributes}
                setAttributes={setAttributes}
                breakpoint={breakpoint}
              />
            )}
          </ResponsiveControl>

          <ResponsiveControl>
            {breakpoint => (
              <MarginControls
                label={__('Margin (px)')}
                attributeKey="blockMargin"
                attributes={attributes}
                setAttributes={setAttributes}
                breakpoint={breakpoint}
              />
            )}
          </ResponsiveControl>
        </PanelBody>
      </InspectorControls>
    </Fragment>
  );
};

export default FoodMenuItemEdit;
