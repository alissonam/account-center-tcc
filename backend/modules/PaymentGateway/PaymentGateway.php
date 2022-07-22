<?php

namespace PaymentGateway;

/**
 * Modulo responsavel por fazer a ponte entre camada de service e os respectivos gateways de pagamento
 * gatewayName é o nome do gateway de pagamento: Vindi, mercado pago etc..
 * Padronizar modulos de gateway de pagamento sendo o nome do modulo : NomeEntidade+NomeGatewayPagamento+Service tudo em PascalCase
 *  Ex: vindi: CustomerVindiService. mercado pago: CustomerMercadoPagoService.
 * Padronizar nome do pacote onde estão os modulos sendo, o nome do gateway de pagamento seguido da palavra gateway, NomeGatewayPagamento+Gateway tudo em PascalCase
 *  Ex vindi: VindiGateway. mercado pago: MercadoPagoGateway
 *
 */
class PaymentGateway {

    public static $gatewayName = "Vindi";

    public static function Customer($acountCenterEntity) {
        $gatewayModule = self::mountGatewayName("Customer");
        return new $gatewayModule($acountCenterEntity);
    }

    public static function Subscription($acountCenterEntity) {
        $gatewayModule = self::mountGatewayName("Subscription");
        return new $gatewayModule($acountCenterEntity);
    }

    private static function mountGatewayName($moduleName) {
        $fullModuleName = $moduleName.self::$gatewayName."Service";
        $gatewayModule = self::$gatewayName."Gateway\\".$fullModuleName;
        return $gatewayModule;
    }
}
