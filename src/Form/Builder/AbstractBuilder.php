<?php

namespace Hardkode\Form\Builder;

use Assert\Assert;
use Hardkode\Form\Builder\Type\Csrf;
use Hardkode\Form\Builder\Validator\Csrf as CsrfValidator;
use Hardkode\Initializer;
use Hardkode\Service\LoggerAwareInterface;
use Hardkode\Service\LoggerAwareTrait;
use Hardkode\Service\SessionAwareInterface;
use Hardkode\Service\SessionAwareTrait;
use Psr\Http\Message\RequestInterface;

/**
 * Class AbstractBuilder
 * @package Hardkode\Form\Builder
 */
abstract class AbstractBuilder implements LoggerAwareInterface, SessionAwareInterface
{

    use LoggerAwareTrait;
    use SessionAwareTrait;

    /** @var array */
    private $formAttributes = [
        'action'  => '',
        'method'  => '',
        'enctype' => ''
    ];

    /** @var AbstractType[] */
    private $fields = [];

    /** @var RequestInterface */
    private $request;

    /** @var array */
    private $data;

    /** @var string */
    private $formId;

    /**
     * AbstractBuilder constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
        $this->formId = md5(get_called_class());
    }

    /**
     * @return self
     */
    public function build()
    {
        $this->create($this->request);

        if ($this->request->getMethod() === 'GET') {
            $this->getSession()->delete('csrf_' . $this->getId());
            $this->appendCsrfToken();
        }

        $this->setFormAttributes($this->request->getUri()->getPath());

        if ($this->request->getMethod() === 'POST') {

            parse_str($this->request->getBody()->getContents(), $this->data);

            foreach ($this->data as $name => $value) {

                if ('csrf' === $name) {
                    // Needs to be filled separately on post request
                    $this->fields['csrf'] = new Csrf(['name' => 'csrf', 'value' => $value], [CsrfValidator::class]);
                }

                if (!$this->fields[$name]) {
                    continue;
                }

                $this->fields[$name]->setValue($value);

            }

        }

        return $this;
    }

    /**
     * @param string $type
     * @param array $definition
     * @param array $validators
     *
     * @throws \ReflectionException
     */
    protected function add(string $type, array $definition, array $validators = [])
    {
        Assert::that($definition)->notEmptyKey('name', 'Attribute `name` is missing.');
        $this->fields[$definition['name']] = Initializer::load($type, [$definition, $validators]);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->formId;
    }

    /**
     * @return string
     */
    public function open()
    {
        return sprintf(
            '<form method="%s" action="%s" enctype="%s">',
            $this->formAttributes['method'],
            $this->formAttributes['action'] ?? null,
            $this->formAttributes['enctype']
        );
    }

    /**
     * @return string
     */
    public function close()
    {
        return $this->getField('csrf') . PHP_EOL . '</form>';
    }

    /**
     * @return bool
     *
     * @throws \ReflectionException
     */
    protected function appendCsrfToken()
    {
        if ($this->session->get('csrf_' . $this->formId)) {
            return false;
        }

        $token = hash('sha256', uniqid());

        $this->add(Csrf::class, [
            'value' => $token,
            'name'  => 'csrf'
        ]);

        $this->session->set('csrf_' . $this->formId, $token);

        return true;
    }

    /**
     * @param string      $action
     * @param string|null $method
     * @param string|null $enctype
     */
    protected function setFormAttributes(string $action, ?string $method = 'post', ?string $enctype = 'application/x-www-form-urlencoded')
    {
        $this->formAttributes['action']  = $action;
        $this->formAttributes['method']  = $method;
        $this->formAttributes['enctype'] = $enctype;
    }

    /**
     * @param string $name
     * @return AbstractType
     */
    public function getField(string $name)
    {
        return $this->fields[$name] ?? null;
    }

    /**
     * @return bool
     */
    public function isSubmitted(): bool
    {
        return 'POST' === $this->request->getMethod();
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        $result = array_map(function(AbstractType $field) {
            $field->setSession($this->session);
            $field->setForm($this);
            return $field->isValid();
        }, $this->fields);

        return !in_array(false, $result, true);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param RequestInterface $request
     *
     * @return void
     */
    abstract public function create(RequestInterface $request);

}