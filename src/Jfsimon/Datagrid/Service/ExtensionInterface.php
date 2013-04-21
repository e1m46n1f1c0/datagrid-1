<?php

namespace Jfsimon\Datagrid\Service;

use Jfsimon\Datagrid\Model\Component\Grid;
use Jfsimon\Datagrid\Model\Data\Collection;
use Jfsimon\Datagrid\Model\Data\Entity;
use Jfsimon\Datagrid\Model\Schema;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Extension service interface.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
interface ExtensionInterface
{
    /**
     * Configures options resolver.
     *
     * @param OptionsResolver $resolver
     */
    public function configure(OptionsResolver $resolver);

    /**
     * Tries to guess schema from an entity.
     *
     * @param Entity $entity
     * @param array  $options
     *
     * @return Schema|null
     */
    public function guessSchema(Entity $entity, array $options);

    /**
     * Builds columns schema.
     *
     * @param Schema     $schema
     * @param Collection $collection
     * @param array      $options
     */
    public function buildSchema(Schema $schema, Collection $collection, array $options = array());

    /**
     * Builds grid rows.
     *
     * @param Grid       $grid
     * @param Collection $collection
     * @param array      $options
     */
    public function buildGrid(Grid $grid, Collection $collection, array $options = array());

    /**
     * Visits grid.
     *
     * @param Grid  $grid
     * @param array $options
     */
    public function visit(Grid $grid, array $options = array());

    /**
     * Returns named columns.
     *
     * @param Column[]
     */
    public function getColumns();

    /**
     * Returns named handlers.
     *
     * @return HandlerInterface[]
     */
    public function getHandlers();

    /**
     * Returns named components visitors.
     *
     * @return VisitorInterface[]
     */
    public function getVisitors();
}