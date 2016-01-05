<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\MailerBundle\Filter;

/**
 * The filter registry of template and transport.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FilterRegistry implements FilterRegistryInterface
{
    /**
     * @var TemplateFilterInterface[]
     */
    protected $templateFilters;

    /**
     * @var TransportFilterInterface[]
     */
    protected $transportFilters;

    /**
     * Constructor.
     *
     * @param TemplateFilterInterface[]  $templateFilters  The template filters
     * @param TransportFilterInterface[] $transportFilters The transport filters
     */
    public function __construct(array $templateFilters = array(), array $transportFilters = array())
    {
        $this->templateFilters = array();
        $this->transportFilters = array();

        foreach ($templateFilters as $filter) {
            $this->addTemplateFilter($filter);
        }

        foreach ($transportFilters as $filter) {
            $this->addTransportFilter($filter);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addTemplateFilter(TemplateFilterInterface $filter)
    {
        $this->templateFilters[] = $filter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateFilters()
    {
        return $this->templateFilters;
    }

    /**
     * {@inheritdoc}
     */
    public function addTransportFilter(TransportFilterInterface $filter)
    {
        $this->transportFilters[] = $filter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransportFilters()
    {
        return $this->transportFilters;
    }
}
