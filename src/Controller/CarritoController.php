<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Knp\Snappy\Pdf;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;



/**
 * @Route("/carrito")
 * 
 */
class CarritoController extends AbstractController
{
    //propiedad para utilizar la sesion de symfony
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/index", name="carrito")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        //Se prepara para coger la sesión de symfony
        $session = $this->requestStack->getSession();
        //Preparación de variables
        $array = [];
        //conseguimos la sesión de symfony
        $carro = $session->get('carrito');
        $preciototal = 0;
        //Si es nulo creamos el carro vacío,si no, recuperamos los productos 
        if (is_null($carro)) {
            $productos = [];
            $array = $productos;
        } else {
            $preciototal = 0;
            //True en json_decode crea el array asociativo
            $productos = json_decode($carro, true);
            //metemos todos los productos como un objeto en un array
            foreach ($productos as $prod => $cod) {
                $preciot = 0;
                //Busca el producto en la BD
                $producto = $entityManager
                ->getRepository(Product::class)
                ->find($prod);
                //calculamos cantidad y precio totales
                $preciot = $producto->getPrecio() - ($producto->getPrecio() * ($producto->getDescuento() / 100)) ;
                //añadimos al array
                array_push($array, $producto);
                $preciototal=$preciototal + $preciot;
            }
            
        }
        //pasamos los productos y las demas variables necesarias a la plantilla
        return $this->render('carrito/index.html.twig', [
            'productos' => $array,
            'preciototal' => round($preciototal, 2)
        ]);
    }

    /**
     * @Route("/addcarrito", name = "add_carro")
     * Añadir al carrito (Se utiliza AJAX)
     * 
     * @return void
     */
    public function add(Request $request)
    {
        $session = $this->requestStack->getSession();
        //Cogemos de la sesion el carrito
        $carro = $session->get('carrito');
        $productos = null;

        //Si el carro es nulo, creamos el array vacío, sino conseguimos los productos
        if (is_null($carro)) {
            $productos = [];
        } else {
            $productos = json_decode($carro, TRUE);
        }

        //Conseguimos del AJAX del producto
        $producto = json_decode($request->getContent());
        //Comprobamos si ya hay alguno en el carrito o es nuevo
        if (isset($productos[$producto])) {
            #no se hase na
        } else {
            $productos[$producto] = 1;
        }

        //Metemos el producto en el array de productos
        //añadimos al carrito
        $session->set('carrito', json_encode($productos, true));

        //Devolvemos un JsonResponse
        return new JsonResponse($productos);
    }

    /**
     * @Route("/vaciar_carrito", name = "clean_carro")
     *
     * 
     * @return void
     */
    public function VaciarCarrito()
    {
        $session = $this->requestStack->getSession();

        //Ponemos el carrito en nulo y enviamos de vuelta al index del carrito con todas las variables vacías
        $session->set('carrito', null);
        $array = [];
        return $this->render('carrito/index.html.twig', [
            'productos' => $array,
            'preciototal' => 0,
            'cantidad' => 0
        ]);
    }


    /**
     * @Route("/borrarcarrito", name = "delete_carro")
     *
     * 
     * @return void
     */
    public function BorrarCarrito(Request $request)
    {
        $session = $this->requestStack->getSession();

        //Obtenemos el carrito de la sesión y lo metemos en un array asociativo
        $carro = $session->get('carrito');
        $productos = json_decode($carro, true);
        //Obtenemos el producti a eliminar del AJAX
        $producto = json_decode($request->getContent());

        //Si existe, se le resta uno a la cantidad total y si es 0, se elimina del array
        if (isset($productos[$producto])) {
            unset($productos[$producto]);
        }

        //añadimos al carrito
        $session->set('carrito', json_encode($productos));

        //Devolvemos un JsonResponse
        return new JsonResponse($productos);
    }

     /**
     * @Route("/envcorreo", name = "envcorreo")
     * Compra del carrito, envio del correo y descarga del pdf
     * 
     * @return void
     */
    public function FinalizarCompra(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        //Conseguimos el email del post y declaramos variables
        //Actualmente, no funciona con gmail y debe usarse un correo de pago
        
        $session = $this->requestStack->getSession();
        //$name= $this->getUser()->getEmail();
        $array = [];
        $preciototal = 0;
        //obtenemos el carrito 
        $carro = $session->get('carrito');
        $productos = json_decode($carro, true);
        //se buscan los objetos y se meten en un array, también calculamos precios y cantidades
        foreach ($productos as $prod => $cod) {
            $producto = $entityManager
                ->getRepository(Product::class)
                ->find($prod);
            $preciot = $producto->getPrecio() - ($producto->getPrecio() * ($producto->getDescuento() / 100)) ;
            array_push($array, $producto);
            $preciototal=$preciototal + $preciot;
        }

        //Preparamos el email
        /*
        $email = (new TemplatedEmail())
            ->from('bukingWEB@hotmail.com')
            ->to(new Address($name))
            ->subject('¡Gracias por tu compra!')

            // ruta del twig a utilizar
            ->htmlTemplate('carrito/compra.html.twig')

            // pasamos variables para el twig
            ->context([
                'productos' => $array,
                'precio_final' => round($preciototal, 2),
            ]);
        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
        }
        */
        $html = $this->renderView(
            'carrito/compra.html.twig',
            [
                'productos' => $array,
                'precio_final' => round($preciototal, 2),
            ]
        );

        //vaciamos el carrito
        $session->set('carrito', null);

        //LLama a la función para descargar el PDF
        return $this->crea_pdf($html);
    }

      /**
     * Genera el PDF desde un HTML
     * @Route("/pdf", name="pdf")
     * @param [type] $html
     * @return void
     */
    private function crea_pdf($html)
    {
        $filename = sprintf('factura-%s.pdf', date('Y-m-d_h-i'));
        // Programa encargado de la conversión de html a pdf
        $pdf = new Pdf("\"C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe\"");
        //Creamos el PDF
        return new Response(
            $pdf->getOutputFromHtml($html, array(
                'default-header' => null,
                'encoding' => 'utf-8',
                'images' => true,
                'margin-right'  => 7,
                'margin-left'  => 7,
            )),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }
}