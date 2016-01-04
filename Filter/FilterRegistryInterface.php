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
 * Interface for the filter registry of template and transport.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface FilterRegistryInterface
{
    /**
     * Add the template filter.
     *
     * @param TemplateFilterInterface $filter The template filter
     *
     * @return self
     */
    public function addTemplateFilter(TemplateFilterInterface $filter);

    /**
     * Get the template filters.
     *
     * @return TemplateFilterInterface[]
     */
    public function getTemplateFilters();

    /**
     * Add the transport filter.
     *
     * @param TransportFilterInterface $filter The transport filter
     *
     * @return self
     */
    public function addTransportFilter(TransportFilterInterface $filter);

    /**
     * Get the transport filters.
     *
     * @return TransportFilterInterface[]
     */
    public function getTransportFilters();
}
